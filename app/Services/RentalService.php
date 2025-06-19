<?php

namespace App\Services;

use App\Models\Car;
use App\Models\Rental;
use App\Models\Discount;
use App\Models\User;
use App\Models\UserHistory;
use App\Exceptions\ActiveRentalExistsException;
use App\Exceptions\PendingRentalExistsException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use App\Events\CarRented;
use App\Events\PaymentMade;
use App\Events\PendingRentalCleared;

class RentalService
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    public function createRental(array $data)
    {
        if (auth()->user()->hasPendingRental()) {
            throw new PendingRentalExistsException();
        }

        if (auth()->user()->hasOngoingRental()) {
            throw new ActiveRentalExistsException();
        }
        DB::beginTransaction();
        try {
            // Calculate rental details
            $car = Car::findOrFail($data['car_id']);
            $startDate = Carbon::parse($data['start_date']);
            $endDate = Carbon::parse($data['end_date']);
            $days = $startDate->diffInDays($endDate);
            $totalPrice = $car->price * $days;

            // Apply discount if provided
            $discount = null;
            if (!empty($data['discount_name'])) {
                $discount = Discount::where('name', $data['discount_name'])->activeDiscount($data['discount_name'])->first();
                if ($discount) {
                    $totalPrice = $totalPrice * (1 - $discount->percentage / 100);
                }
            }

            // Create Stripe payment intent
            $paymentIntent = PaymentIntent::create([
                'amount' => (int)($totalPrice * 100), // Convert to cents
                'currency' => 'usd',
                'payment_method_types' => ['card'],
                'metadata' => [
                    'car_id' => $car->id,
                    'start_date' => $startDate->format('Y-m-d'),
                    'end_date' => $endDate->format('Y-m-d'),
                    'days' => $days,
                    'discount_applied' => $discount ? $discount->name : null
                ]
            ]);

            if (!$paymentIntent || !$paymentIntent->id) {
                throw new \Exception('Failed to create payment intent');
            }

            // Create rental record
            $rental = Rental::create([
                'user_id' => auth()->id(),
                'car_id' => $car->id,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'total_price' => $totalPrice,
                'status' => 'pending',
                'discount_id' => $discount?->id,
                'payment_intent_id' => $paymentIntent->id,
                'client_secret' => $paymentIntent->client_secret
            ]);

            // Create user history record using UserHistory model
            UserHistory::create([
                'user_id' => auth()->id(),
                'car_id' => $car->id,
                'rent_date' => $startDate,
                'rental_id' => $rental->id,
                'return_date' => $endDate,
                'total_price' => $totalPrice,
                'status' => 'pending',
                'payment_method' => 'stripe',
                'payment_status' => 'pending'
            ]);

            DB::commit();
            event(new CarRented(
                auth()->user(),
                $car,
                [
                    'duration' => $days,
                    'total_price' => $totalPrice,
                    'start_date' => $startDate->format('Y-m-d'),
                    'end_date' => $endDate->format('Y-m-d')
                ]
            ));
            return [
                'rental' => $rental,
                'client_secret' => $paymentIntent->client_secret
            ];

        } catch(\Stripe\Exception\ApiErrorException $e) {
            DB::rollBack();
            throw new \Exception('Stripe error: ' . $e->getMessage());
        } catch(\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function confirmPayment(Rental $rental)
    {

        if (!$rental || $rental->status !== 'pending') {
            throw new \Exception('Invalid rental status');
        }
        if (!$rental->payment_intent_id || !$rental->client_secret) {
            throw new \Exception('Payment intent not found for this rental');
        }
        $intent= PaymentIntent::retrieve($rental->payment_intent_id);
        if ($intent->status !== 'succeeded') {
            throw new \Exception('Payment intent not succeeded');
        }
        if ($intent->amount_received < $rental->total_price * 100) {
            throw new \Exception('Payment amount does not match rental total price');
        }

        DB::beginTransaction();
        try {

            $rental->update([
                'status' => 'confirmed'
            ]);


            $rental->car->update(['available_at' => $rental->end_date]);


            UserHistory::where('user_id', $rental->user_id)
                ->where('car_id', $rental->car_id)
                ->where('rent_date', $rental->start_date)
                ->update([
                    'status' => 'confirmed',
                    'payment_status' => 'paid'
                ]);

            DB::commit();
            event(new PaymentMade(auth()->user(), $rental));
            \Log::info('Payment confirmed for rental ID: ' . $rental->id);

            return $rental;

        } catch(\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function cancelRental(Rental $rental)
    {
        DB::beginTransaction();
        try {
            $rental->update([
                'status' => 'cancelled'
            ]);


            UserHistory::where('user_id', $rental->user_id)
                ->where('car_id', $rental->car_id)
                ->where('rent_date', $rental->start_date)
                ->update([
                    'status' => 'cancelled',
                    'payment_status' => 'refunded'
                ]);

            $rental->car->update(['available_at' => null]);

            DB::commit();
            return $rental;

        } catch(\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
    public function clearOldPendingRentals(){
        DB::beginTransaction();
        try {
          $oldPendingRentals = Rental::with('user')->where('status', 'pending')
            ->where('created_at', '<', now()->subMinutes(30))
            ->get();

            if ($oldPendingRentals->isEmpty()) {
                return [];
            }
            foreach ($oldPendingRentals as $rental) {
                $rental->update(['status' => 'cancelled']);
                UserHistory::where('user_id', $rental->user_id)
                    ->where('car_id', $rental->car_id)
                    ->where('rent_date', $rental->start_date)
                    ->update([
                        'status' => 'cancelled',
                        'payment_status' => 'cancelled'
                    ]);
            }

            DB::commit();

            foreach ($oldPendingRentals as $rental) {

                event(new PendingRentalCleared($rental->user, $rental));
                \Log::info('Pending rental cleared for rental ID: ' . $rental->id);
            }
            return $oldPendingRentals;



        } catch(\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function completeRental(Rental $rental)
    {
        DB::beginTransaction();
        try {
            $rental->update(['status' => 'completed']);
            $rental->car->update(['available_at' => null]);
            UserHistory::where('user_id', $rental->user_id)
                ->where('car_id', $rental->car_id)
                ->where('rent_date', $rental->start_date)
                ->update([
                    'status' => 'completed',
                    'payment_status' => 'paid'
                ]);
            DB::commit();
            return $rental;
        } catch(\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }


}

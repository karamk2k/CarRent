<?php

namespace App\Http\Resources\CarRent;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CarResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'model' => $this->model,
            'color' => $this->color,
            'year' => $this->year,
            'price' => $this->price,
            'image_url' => asset('storage/' . $this->image),
            'category' => $this->categoryRes->name,
            'transmissions' => $this->transmissions,
            'seats' => $this->seats,
            'fuel_type' => $this->fuel_type,
            'fuel_capacity' => $this->fuel_capacity,
            'is_favorite' => $this->when(auth()->check(), function() {
                return $this->favorites()->where('user_id', auth()->id())->exists();
            }, false),
            'available_at' => $this->available_at?->toDateTimeString(),
            'created_at' => $this->created_at?->toDateTimeString(),
            'updated_at' => $this->updated_at?->toDateTimeString(),
        ];
    }
}

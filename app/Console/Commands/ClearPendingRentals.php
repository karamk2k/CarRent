<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\RentalService;

class ClearPendingRentals extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */


    protected $signature = 'app:clear-pending-rentals';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'delete all pending rentals that are older than 1 hour';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $rentalsService = app(RentalService::class);
       $res= $rentalsService->clearOldPendingRentals();
       if(count($res)> 0){
        $this->info('All pending rentals older than 1 hour have been cleared.');
        $this->line('You can check the logs for more details.');
       }else{
        $this->info('No pending rentals older than 1 hour were found.');
       }

    }
}

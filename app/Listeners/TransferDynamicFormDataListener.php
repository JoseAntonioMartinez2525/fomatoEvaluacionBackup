<?php

namespace App\Listeners;

use App\Events\DynamicFormDataInserted;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class TransferDynamicFormDataListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(DynamicFormDataInserted $event): void
    {
        // Execute the TransferDynamicFormData command
        Artisan::call('app:transfer-dynamic-form-data');
    }
}

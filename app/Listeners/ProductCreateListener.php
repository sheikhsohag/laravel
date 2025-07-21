<?php

namespace App\Listeners;

use App\Events\ProductCreate;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class ProductCreateListener
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
    public function handle(ProductCreate $event): void
    {
         Log::info('Product created', [
            'product_id' => $event->product->id ?? 'unknown',
            'event' => 'ProductCreate',
            'details' => $event->product->toArray() ?? []
        ]);
    }
}

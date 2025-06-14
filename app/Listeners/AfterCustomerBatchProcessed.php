<?php

namespace App\Listeners;

use App\Events\CustomerBatchProcessed;
use Illuminate\Contracts\Queue\ShouldQueue;

class AfterCustomerBatchProcessed implements ShouldQueue
{
  /**
   * Handle the event.
   *
   * @param CustomerBatchProcessed $event
   * @return void
   */
  public function handle(CustomerBatchProcessed $event): void
  {
    // notification
    logger()->info('Imported customer batch of size: ' . count($event->batch));
  }
}

<?php

namespace App\Listeners;

use App\Events\CustomerBatchMapped;
use App\Events\CustomerBatchValidated;
use App\Mappers\CustomerMapper;

class MapCustomerBatchListener
{
  public function __construct(private CustomerMapper $mapper) {}

  /**
   * Handle the event.
   *
   * @param CustomerBatchValidated $event
   * @return void
   */
  public function handle(CustomerBatchValidated $event): void
  {
    $mapped = array_map(fn($user) => $this->mapper->map($user), $event->rawUsers);

    event(new CustomerBatchMapped($mapped));
  }

}

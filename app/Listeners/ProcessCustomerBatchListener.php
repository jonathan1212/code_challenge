<?php

namespace App\Listeners;

use App\Events\CustomerBatchMapped;
use App\Events\CustomerBatchProcessed;
use App\Repositories\CustomerRepository;
use Doctrine\ORM\EntityManagerInterface;

class ProcessCustomerBatchListener
{
  public function __construct(
    private readonly CustomerRepository $repo,
    private readonly EntityManagerInterface $em,
    private readonly int $batchSize = 50,
  ) {}

  /**
   * Handle the event.
   *
   * @param CustomerBatchMapped $event
   * @return void
   */
  public function handle(CustomerBatchMapped $event): void
  {
    $batch = [];
    foreach ($event->mappedUsers as $user) {
      $batch[] = $user;
      if (count($batch) >= $this->batchSize) {
        $this->persist($batch);
        event(new CustomerBatchProcessed($batch));
        $batch = [];
      }
    }

    if (!empty($batch)) {
      $this->persist($batch);
      event(new CustomerBatchProcessed($batch));
    }
  }

  /**
   * Persists a batch of customers to the database.
   *
   * @param array $batch
   * @throws \Throwable
   */
  private function persist(array $batch): void
  {
    $this->em->getConnection()->beginTransaction();

    try {
      $this->repo->updateOrCreateBatch($batch);
      $this->em->getConnection()->commit();
    } catch (\Throwable $e) {
      $this->em->getConnection()->rollBack();
      throw $e;
    }
  }
}

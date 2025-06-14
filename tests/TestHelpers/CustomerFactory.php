<?php

namespace Tests\TestHelpers;

use App\Mappers\CustomerMapper;
use App\Repositories\CustomerRepository;
use Doctrine\ORM\EntityManagerInterface;

class CustomerFactory
{
  public static function persistRaw($size = 5): array
  {
    $customers = [];

    $em = app(EntityManagerInterface::class);
    $mapper = app(CustomerMapper::class);
    //$dto = $mapper->map($rawData);

    /** @var CustomerRepository $customerRepository */
    $customerRepository = app(CustomerRepository::class);

    for ($i = 0; $i < $size; $i++) {
      $raw = FakeRawUserGenerator::make();
      $dto = $mapper->map($raw);

      $customer = $customerRepository->updateOrCreate($dto);
      $em->persist($customer);

      array_push($customers, $customer);
    }

    $em->flush();

    return $customers;
  }

}

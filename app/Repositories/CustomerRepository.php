<?php

namespace App\Repositories;

use App\DTO\CustomerData;
use App\Entities\Address;
use App\Entities\Coordinate;
use App\Entities\Customer;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;

class CustomerRepository
{
  public function __construct(
    private EntityManagerInterface $em,
    private int $batchSize
  ) { }

  public function findOneByEmail(string $email): ?Customer
  {
    return $this->em->getRepository(Customer::class)
      ->findOneBy(['email' => $email]);
  }

  public function getAll(): array
  {
    return $this->em->getRepository(Customer::class)->findAll();
  }

  public function paginate(int $page = 1, int $limit = 10): Paginator
  {
    $offset = ($page - 1) * $limit;

    $query = $this->em->createQueryBuilder()
      ->select('c', 'a')
      ->from(Customer::class, 'c')
      ->leftJoin('c.address', 'a')
      ->setFirstResult($offset)
      ->setMaxResults($limit)
      ->getQuery();

    return new Paginator($query);
  }

  public function findById(int $id): ?Customer
  {
    return $this->em->find(Customer::class, $id);
  }

  public function updateOrCreate(CustomerData $customerData, ?Customer $customer = null): Customer
  {
    if (!$customer) {
      $customer = new Customer();
    }

    $customer->setGender($customerData->gender);
    $customer->setFirstName($customerData->firstName);
    $customer->setLastName($customerData->lastName);
    $customer->setEmail($customerData->email);
    $customer->setUsername($customerData->username);
    $customer->setPhone($customerData->phone);
    $customer->setCell($customerData->cell);
    $customer->setNat($customerData->nat);
    $customer->setPassword($customerData->password);

    // Address
    $address = $customer->getAddress();
    if (!$address) {
      $address = new Address();
      $address->setCustomer($customer); // set owning side
    }

    $address->setStreetNumber($customerData->address->streetNumber);
    $address->setStreetName($customerData->address->streetName);
    $address->setCity($customerData->address->city);
    $address->setState($customerData->address->state);
    $address->setCountry($customerData->address->country);
    $address->setPostcode($customerData->address->postcode);

    // Handle Coordinate
    $cord = $address->getCoordinate();
    if (!$cord) {
      $cord = new Coordinate();
      $cord->setAddress($address);
      $address->setCoordinate($cord);
    }

    $cord->setLatitude($customerData->address->coordinate->latitude);
    $cord->setLongitude($customerData->address->coordinate->longitude);
    $cord->setAddress($address);

    $customer->setAddress($address);

    return $customer;
  }

  public function updateOrCreateBatch(array $customersData): void
  {
    $counter = 0;
    $emailIndex = [];

    foreach ($customersData as $customerData) {
      $email = $customerData->email;

      // Check if we've already handled this email in this batch
      $customer = $emailIndex[$email] ?? $this->em->getRepository(Customer::class)
        ->findOneBy(['email' => $email]);

      $customerEntity = $this->updateOrCreate($customerData, $customer);

      if (!$customer) {
        $this->em->persist($customerEntity);
      }

      // Save to local map to prevent re-querying
      $emailIndex[$email] = $customerEntity;

      if (++$counter % $this->batchSize === 0) {
        $this->em->flush();
        $this->em->clear();
        $emailIndex = []; // Clear email index because all entities are detached
      }
    }

    $this->em->flush();
    $this->em->clear();
  }
}

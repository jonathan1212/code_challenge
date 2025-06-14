<?php

namespace App\Services;

use App\Contracts\CustomerImporterInterface;
use App\Contracts\RandomUserApiClientInterface;
use App\Entities\Dob;
use App\Entities\IdDocument;
use App\Entities\Registration;
use App\Events\CustomerBatchValidated;
use App\Exceptions\CustomerImportException;
use App\Exceptions\ValidationFailedException;
use App\Mappers\CustomerMapper;
use App\Validator\DtoValidator;
use App\Validator\ValidationResultCollection;

class CustomerImporterService implements CustomerImporterInterface

{
  public function __construct(
    private readonly RandomUserApiClientInterface $apiClient,
    private readonly CustomerMapper $mapper,
    private readonly DtoValidator $dtoValidator,
  ) {}

  /*
   * Imports a batch of customers from the Random User API.
   */
  public function import(int $count = 100): void
  {
    $customers = $this->apiClient->fetchUsers($count);

    $validationResults = $this->validateAll($customers);

    if ($validationResults->hasErrors()) {
      throw new CustomerImportException($validationResults);
    }

    event(new CustomerBatchValidated($customers));

  }
  /**
   * Validates all users and returns a collection of validation results.
   *
   * @param array $users
   * @return ValidationResultCollection
   * @throws ValidationFailedException
   */
  private function validateAll(array $users): ValidationResultCollection
  {
    $results = new ValidationResultCollection();

    foreach ($users as $userData) {
      try {
        $customerData = $this->mapper->map($userData);
        $this->dtoValidator->validate($customerData);
        $results->addValid($customerData);
      } catch (ValidationFailedException $e) {
        $results->addInvalid($userData, $e->getErrorMessages());
      }
    }

    return $results;
  }


}

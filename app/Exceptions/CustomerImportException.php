<?php

namespace App\Exceptions;

use App\Validator\ValidationResultCollection;
use RuntimeException;

class CustomerImportException extends RuntimeException
{
  private ValidationResultCollection $validationResults;

  public function __construct(
    ValidationResultCollection $validationResults,
    string $message = 'Customer import validation failed',
    int $code = 0,
    ?\Throwable $previous = null
  ) {
    parent::__construct($message, $code, $previous);
    $this->validationResults = $validationResults;
  }

  public function getValidationResults(): ValidationResultCollection
  {
    return $this->validationResults;
  }

  public function getErrorMessages(): string
  {
    return $this->validationResults->getFormattedErrorMessages();
  }

  public function getInvalidEntries(): array
  {
    return $this->validationResults->getInvalidEntries();
  }
}

<?php

namespace App\Exceptions;

use Symfony\Component\Validator\ConstraintViolationListInterface;

class ValidationFailedException extends \RuntimeException
{
  public function __construct(
    private ConstraintViolationListInterface $violations,
    string $message = 'Validation failed',
    int $code = 0,
    ?\Throwable $previous = null
  ) {
    parent::__construct($message, $code, $previous);
  }

  public function getViolations(): ConstraintViolationListInterface
  {
    return $this->violations;
  }

  public function getErrorMessages(): array
  {
    $errors = [];
    foreach ($this->violations as $violation) {
      $errors[$violation->getPropertyPath()][] = $violation->getMessage();
    }
    return $errors;
  }
}

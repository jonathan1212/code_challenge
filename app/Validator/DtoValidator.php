<?php

namespace App\Validator;

use App\Exceptions\ValidationFailedException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class DtoValidator
{
  public function __construct(private ValidatorInterface $validator) {}

  /**
   * @throws ValidationFailedException
   */
  public function validate(object $dto): void
  {
    $violations = $this->validator->validate($dto);

    if (count($violations) > 0) {
      throw new ValidationFailedException($violations);
    }
  }

}

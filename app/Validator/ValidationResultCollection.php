<?php

namespace App\Validator;

class ValidationResultCollection
{
  private array $valid = [];
  private array $invalid = [];

  public function addValid(object $dto): void
  {
    $this->valid[] = $dto;
  }

  public function addInvalid(array $rawData, array $errors): void
  {
    $this->invalid[] = [
      'data' => $rawData,
      'errors' => $errors
    ];
  }

  public function hasErrors(): bool
  {
    return !empty($this->invalid);
  }

  public function getValidCount(): int
  {
    return count($this->valid);
  }

  public function getInvalidCount(): int
  {
    return count($this->invalid);
  }

  public function getInvalidEntries(): array
  {
    return $this->invalid;
  }

  public function getFormattedErrorMessages(): string
  {
    $messages = [];

    foreach ($this->invalid as $entry) {
      $errors = $entry['errors'];

      $errorLines = [];
      foreach ($errors as $field => $fieldErrors) {
        $fieldErrors = (array)$fieldErrors;
        foreach ($fieldErrors as $error) {
          $errorLines[] = "- $field: $error";
        }
      }
      $messages[] = "Validation failed :\n" . implode("\n", $errorLines);
    }

    return implode("\n\n", $messages);
  }
}

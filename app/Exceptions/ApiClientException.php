<?php

namespace App\Exceptions;

class ApiClientException extends \RuntimeException
{
  public function __construct(
    string $message = "API client error",
    int $code = 0,
    ?\Throwable $previous = null,
    private ?array $response = null
  ) {
    parent::__construct($message, $code, $previous);
  }

  public function getResponse(): ?array
  {
    return $this->response;
  }
}

<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class CoordinateData
{
  public function __construct(
    #[Assert\NotBlank]
    #[Assert\Regex(pattern: '/^[-+]?[0-9]{1,3}\.[0-9]+$/')]
    public readonly string $latitude,

    #[Assert\NotBlank]
    #[Assert\Regex(pattern: '/^[-+]?[0-9]{1,3}\.[0-9]+$/')]
    public readonly string $longitude,
  ) {}
}

<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class AddressData
{
  public function __construct(
    #[Assert\NotBlank]
    #[Assert\Length(max: 100)]
    public readonly string $streetName,

    #[Assert\NotBlank]
    #[Assert\Positive]
    public readonly int $streetNumber,

    #[Assert\NotBlank]
    #[Assert\Length(max: 50)]
    public readonly string $city,

    #[Assert\NotBlank]
    #[Assert\Length(max: 50)]
    public readonly string $state,

    #[Assert\NotBlank]
    public readonly string $country,

    #[Assert\NotBlank]
    #[Assert\Length(max: 20)]
    public readonly string $postcode,

    #[Assert\Valid]
    public readonly CoordinateData $coordinate,

  ) {}
}

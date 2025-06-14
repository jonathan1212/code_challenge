<?php

namespace App\DTO;

use App\Enum\GenderEnum;
use Symfony\Component\Validator\Constraints as Assert;

class CustomerData
{
  public function __construct(
    #[Assert\NotBlank]
    #[Assert\Length(min: 2, max: 50)]
    public readonly string $firstName,

    #[Assert\NotBlank]
    #[Assert\Length(min: 2, max: 50)]
    public readonly string $lastName,

    #[Assert\NotBlank]
    #[Assert\Email]
    #[Assert\Length(max: 180)]
    public readonly string $email,

    #[Assert\NotBlank]
    #[Assert\Length(min: 3, max: 50)]
    public readonly string $username,

    #[Assert\NotBlank]
    #[Assert\Type(type: GenderEnum::class)]
    public readonly GenderEnum $gender,

    #[Assert\NotBlank]
    #[Assert\Length(min: 6, max: 20)]
    public readonly string $phone,

    #[Assert\NotBlank]
    #[Assert\Length(min: 6, max: 20)]
    public readonly string $cell,

    #[Assert\NotBlank]
    public readonly string $nat,

    #[Assert\NotBlank]
    #[Assert\Length(exactly: 32)] // MD5 hash length
    public readonly string $password,

    #[Assert\Valid]
    public readonly AddressData $address,
  ) {}
}

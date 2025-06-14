<?php

namespace Tests\Unit;

use App\DTO\AddressData;
use App\DTO\CoordinateData;
use App\DTO\CustomerData;
use App\DTO\TimezoneData;
use App\Enum\GenderEnum;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use PHPUnit\Framework\TestCase;

class CustomerDataTest extends TestCase
{
  private ValidatorInterface $validator;

  protected function setUp(): void
  {
    $this->validator = Validation::createValidatorBuilder()
      ->enableAttributeMapping()
      ->getValidator();
  }

  public function test_valid_customer_data_passes_validation(): void
  {
    $dto = new CustomerData(
      firstName: 'John',
      lastName: 'Doe',
      email: 'john@example.com',
      username: 'johndoe123',
      gender: GenderEnum::MALE,
      phone: '1234567890',
      cell: '1234567890',
      nat: 'AU',
      password: md5('secret'), // 32-char hash
      address: new AddressData(
        streetName: 'Main St',
        streetNumber: 123,
        city: 'New York',
        state: 'NY',
        country: 'USA',
        postcode: '10001',
        coordinate: new CoordinateData('40.7128', '-74.0060'),
      )
    );

    $violations = $this->validator->validate($dto);

    $this->assertCount(0, $violations, (string) $violations);
  }

  public function test_invalid_customer_data_fails_validation(): void
  {
    $dto = new CustomerData(
      firstName: '', // invalid
      lastName: '', // invalid
      email: 'not-an-email',
      username: '',
      gender: GenderEnum::MALE,
      phone: '',
      cell: '',
      nat: '',
      password: 'short', // invalid md5
      address: new AddressData(
        streetName: '',
        streetNumber: 0,
        city: '',
        state: '',
        country: '',
        postcode: '',
        coordinate: new CoordinateData('0', '0'),
      )
    );

    $violations = $this->validator->validate($dto);

    $this->assertGreaterThan(0, count($violations));
  }
}

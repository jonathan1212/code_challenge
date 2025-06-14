<?php

namespace Tests\TestHelpers;

use Faker\Factory;

class FakeRawUserGenerator
{
  public static function make(array $overrides = []): array
  {
    $faker = Factory::create();

    $raw = [
      'name' => [
        'first' => $faker->firstName,
        'last' => $faker->lastName,
      ],
      'email' => $faker->unique()->safeEmail,
      'login' => [
        'username' => $faker->userName,
        'password' => $faker->password,
        'uuid' => $faker->uuid,
        'salt' => $faker->word,
        'sha1' => $faker->sha1,
        'sha256' => $faker->sha256,
      ],
      'gender' => $faker->randomElement(['male', 'female']),
      'phone' => $faker->phoneNumber,
      'cell' => $faker->phoneNumber,
      'nat' => $faker->countryCode,
      'location' => [
        'street' => [
          'name' => $faker->streetName,
          'number' => $faker->numberBetween(1, 9999),
        ],
        'city' => $faker->city,
        'state' => $faker->state,
        'country' => $faker->country,
        'postcode' => $faker->postcode,
        'coordinates' => [
          'latitude' => $faker->latitude,
          'longitude' => $faker->longitude,
        ],
        'timezone' => [
          'offset' => $faker->timezone,
          'description' => $faker->sentence(3),
        ],
      ],
      'picture' => [
        'large' => $faker->imageUrl(400, 400),
        'medium' => $faker->imageUrl(200, 200),
        'thumbnail' => $faker->imageUrl(100, 100),
      ],
    ];

    return array_replace_recursive($raw, $overrides);
  }
}

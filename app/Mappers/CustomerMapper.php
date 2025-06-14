<?php

namespace App\Mappers;

use App\DTO\AddressData;
use App\DTO\CoordinateData;
use App\DTO\CustomerData;
use App\Enum\GenderEnum;

class CustomerMapper
{
  /**
   * Maps raw user data to a CustomerData DTO.
   *
   * @param array $raw
   * @return CustomerData
   */
  public function map(array $raw): CustomerData
  {
    return new CustomerData(
      firstName: $raw['name']['first'],
      lastName: $raw['name']['last'],
      email: $raw['email'],
      username: $raw['login']['username'],
      gender: GenderEnum::from(strtolower($raw['gender'])),
      phone: $raw['phone'],
      cell: $raw['cell'],
      nat: $raw['nat'],
      password: md5($raw['login']['password']),
      address: new AddressData(
        streetName: $raw['location']['street']['name'],
        streetNumber: $raw['location']['street']['number'],
        city: $raw['location']['city'],
        state: $raw['location']['state'],
        country: $raw['location']['country'],
        postcode: (string)$raw['location']['postcode'],
        coordinate: new CoordinateData(
          latitude: $raw['location']['coordinates']['latitude'],
          longitude: $raw['location']['coordinates']['longitude'],
        ),
      ),
    );
  }
}

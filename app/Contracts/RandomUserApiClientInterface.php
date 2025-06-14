<?php

namespace App\Contracts;

interface RandomUserApiClientInterface
{
  public function fetchUsers(int $count = 100, string $nationality = 'AU'): array;
}

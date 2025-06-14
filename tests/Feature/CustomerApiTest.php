<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\RefreshDoctrineDatabase;
use Tests\TestCase;
use Tests\TestHelpers\CustomerFactory;

class CustomerApiTest extends TestCase
{
  use RefreshDatabase;
  use RefreshDoctrineDatabase;

  private readonly array $customers;

  protected function setUp(): void
  {
    parent::setUp();
    $this->refreshDoctrineSchema();

    $this->customers = CustomerFactory::persistRaw(10);
  }

  public function test_index_returns_paginated_customers()
  {
    $response = $this->getJson('/customers?page=1&limit=5');

    $response->assertOk()
      ->assertJsonStructure([
        'data' => [
          '*' => ['fullName', 'email', 'country'],
        ],
        'meta' => ['page', 'limit', 'total', 'pages'],
      ])
      ->assertJsonPath('meta.page', 1)
      ->assertJsonPath('meta.limit', 5)
      ->assertJsonPath('meta.total', count($this->customers))
      ->assertJsonPath('meta.pages',  (int) ceil(count($this->customers) / 5));
  }

  public function test_show_returns_single_customer()
  {
    $customer = $this->customers[0];
    $response = $this->getJson('/customers/' . $customer->getId());

    $response->assertOk()
      ->assertJson([
        'fullName' => $customer->getFullName(),
        'email' => $customer->getEmail(),
        'username' => $customer->getUsername(),
        'gender' => $customer->getGender()->value,
        'country' => $customer->getAddress()->getCountry(),
        'city' => $customer->getAddress()->getCity(),
        'phone' => $customer->getPhone(),
      ]);
  }

  public function test_show_returns_404_when_customer_not_found()
  {
    $nonExistentId = max(array_map(fn($c) => $c->getId(), $this->customers)) + 999;

    $response = $this->getJson("/customers/{$nonExistentId}");

    $response->assertStatus(404)
      ->assertJson(['message' => 'Customer not found']);
  }

  public function test_index_uses_default_pagination_when_not_provided()
  {
    $response = $this->getJson('/customers');

    $response->assertOk()
      ->assertJsonPath('meta.page', 1)
      ->assertJsonPath('meta.limit', 50);
  }
}

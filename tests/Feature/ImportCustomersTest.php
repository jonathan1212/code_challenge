<?php

use App\Contracts\RandomUserApiClientInterface;
use App\Exceptions\ApiClientException;
use App\Exceptions\CustomerImportException;
use App\Services\CustomerImporterService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\RefreshDoctrineDatabase;
use Tests\TestCase;

class ImportCustomersTest extends TestCase
{
  use RefreshDatabase;
  use RefreshDoctrineDatabase;

  protected function setUp(): void
  {
    parent::setUp();
    $this->refreshDoctrineSchema();
  }

  #[DataProvider('customerProvider')]
  public function test_import(
    array $input,
    array $expectedValidRows,
    array $expectedErrors
  ): void
  {
    $mockApiClient = $this->createMock(RandomUserApiClientInterface::class);
    $mockApiClient->method('fetchUsers')->willReturn($input);
    $this->app->instance(RandomUserApiClientInterface::class, $mockApiClient);

    $service = $this->app->make(CustomerImporterService::class);

    try {
      $service->import(count($input));

    } catch (CustomerImportException $e) {
      $caughtErrors = $e->getErrorMessages();

      foreach ($expectedErrors as $expectedError) {
        foreach ($expectedError as $field => $message) {
          $this->assertStringContainsString($field . ': ' . $message, $caughtErrors);
        }
      }
    }

    foreach ($expectedValidRows as $row) {
      $this->assertDatabaseHas('customers', $row);
    }

    $this->assertEquals(count($expectedValidRows), DB::table('customers')->count());
  }

  #[DataProvider('invalidUserDataProvider')]
  public function test_import_fails_with_invalid_data(
    array $input,
    array $expectedErrors
  ): void
  {
    $mockApiClient = $this->createMock(RandomUserApiClientInterface::class);
    $mockApiClient->method('fetchUsers')->willReturn($input);
    $this->app->instance(RandomUserApiClientInterface::class, $mockApiClient);

    $service = $this->app->make(CustomerImporterService::class);

    try {
      $service->import(count($input));

    } catch (CustomerImportException $e) {
      $caughtErrors = $e->getErrorMessages();

      foreach ($expectedErrors as $expectedError) {
        foreach ($expectedError as $field => $message) {
          $this->assertStringContainsString($field . ': ' . $message, $caughtErrors);
        }
      }
    }

  }

  public function test_import_fails_when_api_throws_exception()
  {
    $mockApiClient = $this->createMock(RandomUserApiClientInterface::class);
    $mockApiClient->method('fetchUsers')->willThrowException(new ApiClientException('API error'));

    $this->app->instance(RandomUserApiClientInterface::class, $mockApiClient);


    $this->expectException(ApiClientException::class);
    $this->expectExceptionMessage('API error');

    $service = $this->app->make(CustomerImporterService::class);
    $service->import(100);

    $this->assertEquals(0, DB::table('customers')->count());
  }

  public static function customerProvider(): array
  {
    return [
      'valid customers' => [
        'input' => [
          self::fakeUserData(email: 'user1@example.com', first: 'Alice', last: 'Smith'),
          self::fakeUserData(email: 'user2@example.com', first: 'Bob', last: 'Johnson'),
        ],
        'expectedValidRows' => [
          ['email' => 'user1@example.com', 'first_name' => 'Alice', 'last_name' => 'Smith'],
          ['email' => 'user2@example.com', 'first_name' => 'Bob', 'last_name' => 'Johnson'],
        ],
        'expectedErrors' => [], // no errors expected
      ],
      'missing email' => [
        'input' => [
          self::fakeUserData(email: 'valid@example.com', first: 'Valid', last: 'User'),
          self::fakeUserData(email: '', first: '', last: ''),
        ],
        'expectedValidRows' => [], // no expected valid rows
        'expectedErrors' => [
          ['email' => 'This value should not be blank.'],
          ['firstName' => 'This value should not be blank.'],
          ['lastName' => 'This value should not be blank.'],
        ],
      ],
      'duplicate email' => [
        'input' => [
          self::fakeUserData(email: 'dup@example.com', first: 'Dup1'),
          self::fakeUserData(email: 'dup@example.com', first: 'Dup2', last: 'Doe'),
        ],
        'expectedValidRows' => [
          ['email' => 'dup@example.com', 'first_name' => 'Dup2', 'last_name' => 'Doe'],
        ],
        'expectedErrors' => [],
      ],
    ];
  }

  public static function invalidUserDataProvider(): array
  {
    return [
      'invalid email' => [
        'input' => [
          self::fakeUserData(email: 'testc.om'),
          self::fakeUserData(email: 'valid@example.com', first: 'Valid', last: 'User'),
        ],
        'expectedErrors' => [
          ['email' => 'This value is not a valid email address.'],
        ],
      ],
      'minimum input' => [
        'input' => [
          self::fakeUserData(last: 'A', first: 'J'),
          self::fakeUserData(last: 'A', first: 'J'),
        ],

        'expectedErrors' => [
          ['lastName' => 'This value is too short. It should have 2 characters or more.'],
          ['firstName' => 'This value is too short. It should have 2 characters or more.'],
        ],
      ],
    ];
  }

  private static function fakeUserData(
    string $email = 'john@example.com',
    string $gender = 'male',
    string $first = 'John',
    string $last = 'Doe',
    string $country = 'US'
  ): array
  {
    return [
      'name' => ['first' => $first, 'last' => $last],
      'email' => $email,
      'login' => [
        'username' => 'johndoe',
        'password' => 'secret',
        'uuid' => 'abc-123',
        'salt' => 'salty',
        'sha1' => 'hash1',
        'sha256' => 'hash256',
      ],
      'gender' => $gender,
      'phone' => '123-456',
      'cell' => '123-789',
      'nat' => 'US',
      'picture' => [
        'large' => 'url1',
        'medium' => 'url2',
        'thumbnail' => 'url3',
      ],
      'location' => [
        'street' => ['name' => 'Main', 'number' => 123],
        'city' => 'City',
        'state' => 'State',
        'country' => $country,
        'postcode' => 12345,
        'coordinates' => ['latitude' => '10.1', 'longitude' => '20.2'],
        'timezone' => ['offset' => '+1:00', 'description' => 'UTC+1'],
      ],
    ];
  }
}

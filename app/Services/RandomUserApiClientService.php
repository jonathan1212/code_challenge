<?php

namespace App\Services;

use App\Contracts\RandomUserApiClientInterface;
use App\Exceptions\ApiClientException;
use Illuminate\Http\Client\Factory as HttpClient;
use Illuminate\Http\Client\Response;

class RandomUserApiClientService implements RandomUserApiClientInterface
{
  public function __construct(
    private HttpClient $httpClient,
    private string $apiUrl) {}

  public function fetchUsers(int $count = 100, string $nationality = 'AU'): array
  {
    $response = $this->httpClient->get($this->apiUrl, [
      'nat' => $nationality,
      'results' => $count,
    ]);

    $this->validateResponse($response);

    $data = $response->json();

    return $data['results'] ?? [];
  }

  private function validateResponse(Response $response): void
  {
    if ($response->failed()) {
      throw new ApiClientException(
        'API request failed with status code: ' . $response->status()
      );
    }
  }
}

<?php

namespace App\Http\Controllers;

use App\Entities\Customer;
use App\Repositories\CustomerRepository;
use App\Services\CustomerValidationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
  public function __construct(
    private CustomerRepository $customerRepository,
  ) {}


  /**
   * Display a listing of the customers.
   *
   * @param Request $request
   * @return JsonResponse
   */
  public function index(Request $request): JsonResponse
  {
    $page = max(1, (int) $request->query('page', 1));
    $limit = max(1, (int) $request->query('limit', 50));

    $paginator = $this->customerRepository->paginate($page, $limit);

    $data = array_map(function ($customer) {
      /** @var Customer $customer */
      return [
        'fullName' => $customer->getFullName(),
        'email' => $customer->getEmail(),
        'country' => $customer->getAddress()->getCountry(),
      ];
    }, iterator_to_array($paginator));

    return response()->json([
      'data' => $data,
      'meta' => [
        'page' => $page,
        'limit' => $limit,
        'total' => count($paginator),
        'pages' => ceil(count($paginator) / $limit),
      ],
    ]);
  }

  /**
   * Display the specified customer.
   *
   * @param int $id
   * @return JsonResponse
   */
  public function show(int $id): JsonResponse
  {
    $customer = $this->customerRepository->findById($id);

    if (!$customer) {
      return response()->json(['message' => 'Customer not found'], 404);
    }

    return response()->json([
      'fullName' => $customer->getFullName(),
      'email' => $customer->getEmail(),
      'username' => $customer->getUsername(),
      'gender' => $customer->getGender()->value,
      'country' => $customer->getAddress()->getCountry(),
      'city' => $customer->getAddress()->getCity(),
      'phone' => $customer->getPhone(),
    ]);
  }
}

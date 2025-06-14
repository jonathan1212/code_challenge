<?php

namespace App\Providers;

use App\Contracts\CustomerImporterInterface;
use App\Contracts\RandomUserApiClientInterface;
use App\Listeners\ProcessCustomerBatchListener;
use App\Mappers\CustomerMapper;
use App\Repositories\CustomerRepository;
use App\Repositories\CustomerRepositoryInterface;
use App\Services\CustomerImporterService;
use App\Services\RandomUserApiClientService;
use App\Validator\DtoValidator;
use Doctrine\ORM\EntityManagerInterface;
use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Client\Factory as HttpClient;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\Validator\Mapping\Loader\AttributeLoader;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
      $this->app->bind(CustomerRepository::class, function ($app) {
        return new CustomerRepository(
          $app->make(EntityManagerInterface::class),
          config('customer-importer.batch_size'),
        );
      });

      $this->app->bind(
        RandomUserApiClientInterface::class,
        function ($app) {
          return new RandomUserApiClientService(
            $app->make(HttpClient::class),
            config('customer-importer.api_url')
          );
        }
      );

      $this->app->singleton(ValidatorInterface::class, function () {
        return Validation::createValidatorBuilder()
          ->enableAttributeMapping()
          ->setMappingCache(new ArrayAdapter()) // optional but improves performance
          ->getValidator();
      });

      $this->app->singleton(DtoValidator::class, function ($app) {
        return new DtoValidator(
          $app->make(ValidatorInterface::class)
        );
      });

      $this->app->singleton(CustomerImporterInterface::class, function ($app) {
        return new CustomerImporterService(
          $app->make(RandomUserApiClientInterface::class),
          $app->make(CustomerMapper::class),
          $app->make(DtoValidator::class),
        );
      });


      $this->app->singleton(ProcessCustomerBatchListener::class, function ($app) {
        return new ProcessCustomerBatchListener(
          $app->make(CustomerRepository::class),
          $app->make(EntityManagerInterface::class),
          config('customer-importer.batch_size')
        );
      });

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}

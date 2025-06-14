<?php

namespace App\Console\Commands;

use App\Contracts\CustomerImporterInterface;
use App\Exceptions\CustomerImportException;
use Illuminate\Console\Command;

class ImportCustomersCommand extends Command
{
  protected $signature = 'import:customers {count=100}';
  protected $description = 'Import customers from randomuser.me API';

  public function __construct(private CustomerImporterInterface $importer)
  {
    parent::__construct();
  }

  public function handle(): int
  {
    $count = (int)$this->argument('count');

    try {
      $this->importer->import($count);
      $this->info("Successfully imported customers");
      return Command::SUCCESS;
    } catch (CustomerImportException $e) {
      $this->error("Error importing customers: {$e->getErrorMessages()}");
      return Command::FAILURE;
    } catch (\Throwable $e) {
      $this->error("An unexpected error occurred: {$e->getMessage()}");
      return Command::FAILURE;
    }
  }
}

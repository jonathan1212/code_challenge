<?php

namespace App\Contracts;

interface CustomerImporterInterface
{
  public function import(int $count = 100): void;
}

<?php

namespace Tests;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;

trait RefreshDoctrineDatabase
{
  protected function refreshDoctrineSchema(): void
  {
    /** @var EntityManagerInterface $em */
    $em = app(EntityManagerInterface::class);

    $schemaTool = new SchemaTool($em);
    $metadata = $em->getMetadataFactory()->getAllMetadata();

    if (!empty($metadata)) {
      $schemaTool->dropSchema($metadata);
      $schemaTool->createSchema($metadata);
    }
  }


}

<?php

declare(strict_types=1);

namespace Test\Infra;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;

class Doctrine
{
    private EntityManager $entityManager;

    /** @noinspection SqlResolve */
    public function __construct()
    {
        $isDevMode = true;
        $proxyDir = null;
        $cache = null;
        $useSimpleAnnotationReader = false;
        $config = Setup::createAnnotationMetadataConfiguration(
            [__DIR__ . "/src"],
            $isDevMode,
            $proxyDir,
            $cache,
            $useSimpleAnnotationReader
        );

        // to make test run fast
        $conn = [
            'driver' => 'pdo_sqlite',
            'memory' => true,
        ];
        $em = EntityManager::create($conn, $config);
        /** @noinspection SqlNoDataSourceInspection */
        $em->getConnection()->executeStatement(
            <<< MIGRATION
CREATE TABLE "user" (
    id STRING NOT NULL,
    login STRING NOT NULL
);
CREATE TABLE "activity" (
    id INTEGER NOT NULL, 
    entityName VARCHAR not null,
    actionType VARCHAR not null, 
    entityId VARCHAR(36) not null, 
    actorID INTEGER, 
    createdAt DATETIME not null, 
    ip varchar, 
    changeSet TEXT
);
MIGRATION
        );
        $this->entityManager = $em;
    }

    /**
     * @return EntityManager
     */
    public function getEM() : EntityManager
    {
        return $this->entityManager;
    }
}

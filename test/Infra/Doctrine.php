<?php

declare(strict_types=1);

namespace Test\Infra;

use Doctrine\Common\EventManager;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;

class Doctrine
{
    private EntityManager $entityManager;

    public function __construct()
    {
        $isDevMode = true;
        $proxyDir = null;
        $cache = null;
        $useSimpleAnnotationReader = false;
        $config = Setup::createAnnotationMetadataConfiguration(array(__DIR__."/src"), $isDevMode, $proxyDir, $cache, $useSimpleAnnotationReader);

        // to make test run fast
        $conn = [
            'driver' => 'pdo_sqlite',
            'memory' => true,
        ];
        $em = EntityManager::create($conn, $config);
        $em->getConnection()->executeStatement("
            CREATE TABLE SomeEntity (id STRING NOT NULL);
            CREATE TABLE Activity (id STRING NOT NULL, entityName VARCHAR not null, entityId VARCHAR(36) not null, actorID INTEGER, createdAt DATETIME not null, ip varchar, changeSet TEXT);
        ");
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

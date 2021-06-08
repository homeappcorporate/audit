<?php

declare(strict_types=1);

namespace Functional;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Events;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\Mapping\MappingException;
use Homeapp\AuditBundle\Audit;
use Homeapp\AuditBundle\ChangeSetGenerator;
use Homeapp\AuditBundle\DatabaseStorage;
use Homeapp\AuditBundle\Entity\Activity;
use Homeapp\AuditBundle\EventListener;
use PHPUnit\Framework\TestCase;
use Test\Infra\Doctrine;
use Test\Infra\Entities\User;
use Test\Infra\Faker;
use Test\Infra\Logger;
use Test\Infra\Stub\ActorInfoFetcher;

/**
 * @covers Audit
 */
final class AuditWithDatabaseStorageDriverTest extends TestCase
{
    private Audit $audit;
    private EntityManager $em;
    private Logger $logger;
    private Faker $faker;

    protected function setUp() : void
    {
        $doctrine = new Doctrine();
        $this->em = $doctrine->getEM();
        $this->logger = new Logger();
        $this->faker = faker();
        $this->audit = new Audit(
            new DatabaseStorage(
                $this->em,
                $this->logger
            )
        );
        $eventListener = new EventListener(
            $this->audit,
            new ActorInfoFetcher($this->faker),
            new ChangeSetGenerator(),
        );
        $this->em->getEventManager()->addEventListener(
            Events::prePersist,
            $eventListener
        );
        $this->em->getEventManager()->addEventListener(
            Events::preFlush,
            $eventListener
        );
        parent::setUp();
    }

    protected function tearDown() : void
    {
        $this->em->clear();
        $this->em->close();
        parent::tearDown();
    }

    /**
     * @throws ORMException|MappingException
     */
    public function testPersistWhoCreatedEntity() : void
    {
        $user = new User($this->faker->numberBetween());
        $this->em->persist($user);
        $this->em->flush();
        $this->em->clear();

        self::assertSame(
            1,
            $this->em->getRepository(Activity::class)->count([])
        );
    }
}

<?php

declare(strict_types=1);

namespace Functional;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Events;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\Mapping\MappingException;
use Homeapp\AuditBundle\ActionTypeEnum;
use Homeapp\AuditBundle\Audit;
use Homeapp\AuditBundle\DatabaseStorage;
use Homeapp\AuditBundle\EventListener;
use PHPUnit\Framework\TestCase;
use Test\Infra\Doctrine;
use Test\Infra\Entities\User;
use Test\Infra\Entities\UserRole;
use Test\Infra\Faker;
use Test\Infra\Logger;
use Test\Infra\Stub\ActorInfoFetcher;

/**
 * @covers Audit
 * @psalm-suppress UnusedClass
 */
final class AuditWithDatabaseStorageDriverTest extends TestCase
{
    private Audit $audit;
    private EntityManager $em;
    private Logger $logger;
    private Faker $faker;

    protected function setUp(): void
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
        );
        $this->em->getEventManager()->addEventListener(
            Events::postPersist,
            $eventListener
        );
        $this->em->getEventManager()->addEventListener(
            Events::postFlush,
            $eventListener
        );
        $this->em->getEventManager()->addEventListener(
            Events::preUpdate,
            $eventListener
        );
        parent::setUp();
    }

    protected function tearDown(): void
    {
        $this->em->clear();
        $this->em->close();
        parent::tearDown();
    }

    /**
     * @throws ORMException|MappingException
     * @test
     */
    public function activityRecordWithCreateTypeWhenEntityFirstTimeBeenPersisted(): void
    {
        $user = $this->makeUser();
        $this->em->persist($user);
        $this->em->flush();
        $this->em->clear();

        $activity = $this->em->getRepository(Activity::class)->findOneBy([
                'entityName' => User::class,
                'entityId' => $user->getId(),
                'actionType' => ActionTypeEnum::CREATE,
            ]);
        self::assertNotNull($activity);
    }

    private function makeUser(): User
    {
        return new User($this->faker->numberBetween(), $this->faker->e164PhoneNumber());
    }

    /**
     * @throws ORMException|MappingException
     * @test
     */
    public function activityRecordWithUpdateAfterFlushingExistedEntity(): void
    {
        $user = $this->makeUser();
        $this->em->persist($user);
        $oldLogin  = $user->getLogin();
        $this->em->flush();
        $userId = $user->getId();
        $this->em->clear();
        unset($user);

        /** @var User $user */
        $user = $this->em->getRepository(User::class)->findOneBy(['id' => $userId]);
        $user->setLogin($this->faker->e164PhoneNumber());
        $newLogin = $user->getLogin();
        $this->em->flush();
        $this->em->clear();

        $activity =
            $this->em->getRepository(Act::class)->findOneBy([
             'entityName' => User::class,
             'actionType' => ActionTypeEnum::UPDATE,
         ]);
        self::assertNotNull($activity);
        self::assertSame(
            [
                'login' => [
                    $oldLogin,
                    $newLogin,
                ],
            ],
            $activity->getChangeSet()
        );
    }

    /**
     * @throws ORMException|MappingException
     * @test
     */
    public function createUserWithARole(): void
    {
        $this->audit->addEntityToTrack(User::class);
        $user = $this->makeUser();
        $role = new UserRole($user, 'admin');
        $this->em->persist($user);
        $user->addRole($role);


        $this->em->flush();
        $userId = $user->getId();
        $this->em->clear();

        $activities = $this->em->getRepository(Act::class)->findBy([
//             'entityName' => User::class,
             'actionType' => ActionTypeEnum::CREATE,
         ]);
        self::assertCount(1, $activities);
        /** @var Act $activity */
        $activity = reset($activities);
        self::assertSame(
            [
                'id' => $user->getId(),
                'login' => $user->getLogin(),
                'roles' => [
                    'id' => $role->getId(),
                    'role' => $role->getRole(),
                ]
            ],
            $activity->getChangeSet()
        );
        dd($activity);

    }
}

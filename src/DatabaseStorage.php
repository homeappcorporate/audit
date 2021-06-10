<?php

declare(strict_types=1);

namespace Homeapp\AuditBundle;

use Doctrine\ORM\EntityManagerInterface;
use Homeapp\AuditBundle\Entity\Activity;
use Psr\Log\LoggerInterface;

/**
 * @internal
 */
class DatabaseStorage implements StorageInterface
{
    private EntityManagerInterface $em;
    private LoggerInterface $logger;

    public function __construct(EntityManagerInterface $em, LoggerInterface $logger)
    {
        $this->em = $em;
        $this->logger = $logger;
    }

    public function send(ActivityData ...$data): void
    {
        foreach ($data as $d) {
            try {
                $this->em->persist(
                    new Activity(
                        $d->getEntityName(),
                        $d->getActionType(),
                        $d->getEntityId(),
                        $d->getActorId(),
                        $d->getCreatedAt(),
                        $d->getIp(),
                        $d->getChangeSet(),
                    )
                );
            } catch (\Exception $e) {
                $this->logger->error(
                    'Enable to save audit log to database',
                    [
                        'exception' => (string)$e,
                    ]
                );
            }
        }
    }
}

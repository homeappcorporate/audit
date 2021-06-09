<?php

declare(strict_types=1);

namespace Homeapp\AuditBundle;

use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManager;
use Psr\Log\LoggerInterface;
use Ramsey\Uuid\Uuid;

/**
 * @internal
 */
class DatabaseStorage implements StorageInterface
{
    private EntityManager $em;
    private LoggerInterface $logger;

    public function __construct(EntityManager $em, LoggerInterface $logger)
    {
        $this->em = $em;
        $this->logger = $logger;
    }

    public function send(ActivityData ...$data): void
    {
        foreach ($data as $d) {
            try {
                $this->em->getConnection()->insert('activity', [
                    'id' => Uuid::uuid6()->toString(),
                    'entityName' => $d->getEntityName(),
                    'entityId' => $d->getEntityId(),
                    'actionType' => $d->getActionType(),
                    'actorId' => $d->getActorId(),
                    'ip' => $d->getIp(),
                    'createdAt' =>  $d->getCreatedAt()->format(DATE_RFC3339),
                    'changeSet' => json_encode($d->getChangeSet()),
                ]);
            } catch (Exception $e) {
                throw $e;
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

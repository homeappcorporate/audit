<?php

declare(strict_types=1);

namespace Homeapp\AuditBundle;

use Doctrine\DBAL\Exception;
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
                        $d->getRequestId()->toString(),
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

    /**
     * @throws Exception
     */
    public function insert(ActivityData $d): void
    {
        $meta = $this->em->getClassMetadata(Activity::class);
        $a = new Activity(
            $d->getEntityName(),
            $d->getActionType(),
            $d->getRequestId()->toString(),
            $d->getEntityId(),
            $d->getActorId(),
            $d->getCreatedAt(),
            $d->getIp(),
            $d->getChangeSet()
        );
        $fileds = $meta->getFieldNames();
        $data = [];
        $types = [];
        foreach ($fileds as $field) {
            $column = $meta->getColumnName($field);
            $meta->getTypeOfField($field);
            /** @psalm-suppress MixedAssignment */
            $data[$column] = $meta->getFieldValue($a, $field);
            $types[$column] = $meta->getTypeOfField($field);
        }

        $this->em->getConnection()->insert(
            $meta->getTableName(),
            $data,
            $types
        );
    }
}

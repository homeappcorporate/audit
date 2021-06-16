<?php

declare(strict_types=1);

namespace Homeapp\AuditBundle;

use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Homeapp\AuditBundle\Entity\Activity;

/**
 * @internal
 */
class DatabaseStorage implements StorageInterface
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
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
        $fields = $meta->getFieldNames();
        $data = [];
        $types = [];
        foreach ($fields as $field) {
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

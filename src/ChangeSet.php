<?php

declare(strict_types=1);

namespace Homeapp\AuditBundle;

use Doctrine\ORM\EntityManagerInterface;

class ChangeSet implements ChangeSetInterface
{
    private EntityManagerInterface $em;
    private IdentifierExtractor $extractor;

    public function __construct(EntityManagerInterface $em, IdentifierExtractor $extractor)
    {
        $this->em = $em;
        $this->extractor = $extractor;
    }

    private function changeSet(object $entity): array
    {
        $class = get_class($entity);
        $meta = $this->em->getClassMetadata($class);
        $fields = $meta->getFieldNames();
        $association = $meta->getAssociationNames();
        $data = [];
        foreach ($association as $a) {
            $value = $meta->getFieldValue($entity, $a);
            $data[$a] = $this->extractor->getIdentifier($value);
        }

        foreach ($fields as $field) {
            $column = $meta->getColumnName($field);
            $data[$column] = $meta->getFieldValue($entity, $field);
        }
        return $data;
    }

    public function forCreate(object $entity): array
    {
        return $this->changeSet($entity);
    }
}

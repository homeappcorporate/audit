<?php

declare(strict_types=1);

namespace Homeapp\AuditBundle;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\MappingException;
use Doctrine\ORM\PersistentCollection;

class ChangeSet implements ChangeSetInterface
{
    private EntityManagerInterface $em;
    private IdentifierExtractor $extractor;

    public function __construct(EntityManagerInterface $em, IdentifierExtractor $extractor)
    {
        $this->em = $em;
        $this->extractor = $extractor;
    }

    /**
     * @throws MappingException
     */
    private function changeSet(object $entity): array
    {
        $class = get_class($entity);
        $meta = $this->em->getClassMetadata($class);
        $fields = $meta->getFieldNames();
        $data = $this->relation($entity);

        foreach ($fields as $field) {
//            $column = $meta->getColumnName($field);
            /** @psalm-suppress MixedAssignment */
            $data[$field] = $meta->getFieldValue($entity, $field);
        }

        return $data;
    }

    /**
     * @throws MappingException
     */
    public function relation(object $entity): array
    {
        $class = get_class($entity);
        $meta = $this->em->getClassMetadata($class);
        $associationNames = $meta->getAssociationNames();
        $data = [];
        foreach ($associationNames as $associationName) {
            /** @var object|null */
            $value = $meta->getFieldValue($entity, $associationName);
            if ($value instanceof PersistentCollection) {
                if (!$value->isInitialized() && !$value->isDirty()) {
                    continue;
                }
                $data[$associationName] = [];
                /** @var object $item */
                foreach ($value as $item) {
                    $data[$associationName] = $this->extractor->getIdentifier($item);
                }
            } else {
                if ($value) {
                    $data[$associationName] = $this->extractor->getIdentifier($value);
                }
            }
        }

        return $data;
    }

    /**
     * @throws MappingException
     */
    public function get(object $entity): array
    {
        return $this->changeSet($entity);
    }
}

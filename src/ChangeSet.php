<?php

declare(strict_types=1);

namespace Homeapp\AuditBundle;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\MappingException;

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
        $association = $meta->getAssociationNames();
        $data = [];
        foreach ($association as $a) {
            /** @var object */
            $value = $meta->getFieldValue($entity, $a);
            $data[$a] = $this->extractor->getIdentifier($value);
        }

        foreach ($fields as $field) {
            $column = $meta->getColumnName($field);
            /** @psalm-suppress MixedAssignment */
            $data[$column] = $meta->getFieldValue($entity, $field);
        }
        return $data;
    }

    /**
     * @throws MappingException
     */
    public function get(object $entity) : array
    {
        return $this->changeSet($entity);
    }
}

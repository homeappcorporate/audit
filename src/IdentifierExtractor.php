<?php

declare(strict_types=1);

namespace Homeapp\AuditBundle;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\MappingException;

/**
 * @internal
 * @psalm-suppress MixedReturnStatement
 * @psalm-suppress MissingClosureReturnType
 */
class IdentifierExtractor
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @throws MappingException
     * @psalm-suppress MixedInferredReturnType
     * @return string|int
     */
    public function getIdentifier(object $entity)
    {
        $entityClass = get_class($entity);
        $meta = $this->em->getClassMetadata($entityClass);

        $identifier = $meta->getSingleIdentifierFieldName();
        return (function (string $identifier) {
            return $this->$identifier;
        })->call($entity, $identifier);
    }
}

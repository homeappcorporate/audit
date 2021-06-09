<?php


namespace Homeapp\AuditBundle;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\MappingException;

/**
 * @internal
 */
class IdentifierExtractor
{
    private EntityManager $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * @throws MappingException
     * @psalm-suppress MixedInferredReturnType
     */
    public function getIdentifier(object $entity): string
    {
        $entityClass = get_class($entity);
        $meta = $this->em->getClassMetadata($entityClass);

        $identifier = $meta->getSingleIdentifierFieldName();
        /** @psalm-suppress  MixedReturnStatement */
        return (function (string $identifier): string {
            return (string)$this->$identifier;
        })->call($entity, $identifier);
    }
}
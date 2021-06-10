<?php


namespace Homeapp\AuditBundle;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\MappingException;

/**
 * @internal
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
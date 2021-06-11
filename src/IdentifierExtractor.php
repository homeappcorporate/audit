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
     * @return string|int
     */
    public function getIdentifier(object $entity)
    {
        $entityClass = get_class($entity);
        $meta = $this->em->getClassMetadata($entityClass);

        $identifier = $meta->getSingleIdentifierFieldName();
        /** @psalm-suppress  MixedReturnStatement */
        return (function (string $identifier) {
            return $this->$identifier;
        })->call($entity, $identifier);
    }
}
<?php

declare(strict_types=1);

namespace Homeapp\AuditBundle;

use Doctrine\Common\Util\ClassUtils;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\MappingException;
use Psr\Log\LoggerInterface;

/**
 * @internal
 * @psalm-suppress MixedReturnStatement
 * @psalm-suppress MissingClosureReturnType
 */
class IdentifierExtractor
{
    private EntityManagerInterface $em;
    private LoggerInterface $logger;

    public function __construct(EntityManagerInterface $em, LoggerInterface $logger)
    {
        $this->em = $em;
        $this->logger = $logger;
    }

    /**
     * @return string|int|null
     * @throws MappingException
     * @psalm-suppress MixedInferredReturnType
     */
    public function getIdentifier(object $entity)
    {
        $entityClass = get_class($entity);
        $meta = $this->em->getClassMetadata($entityClass);

        $identifier = $meta->getSingleIdentifierFieldName();


        $getIdentifier = function (string $identifier, LoggerInterface $logger, EntityManagerInterface $em, \Closure $getIdentifier)  {
            if (!property_exists($this, $identifier)) {
                $logger->debug(
                    'Class: {class} does not have $identifier: {name}, realClass: {real}',
                    [
                        'class' => get_class($this),
                        'name' => $identifier,
                        'real' => ClassUtils::getClass($this),
                    ]
                );

                return null;
            }
            $value = $this->$identifier;
            if (is_object($value)) {
                $entityClass = get_class($value);
                $meta = $this->em->getClassMetadata($entityClass);

                $identifier = $meta->getSingleIdentifierFieldName();
                return $getIdentifier->call($value,  $identifier, $logger, $em, $getIdentifier);
            }
            return $value;
        };
        $getIdentifier->call($entity, $identifier, $this->logger, $this->);
    }
}

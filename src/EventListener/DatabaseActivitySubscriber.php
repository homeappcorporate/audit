<?php
declare(strict_types=1);

namespace Homeapp\AuditBundle\EventListener;

use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Mapping\MappingException;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Homeapp\AuditBundle\ActionTypeEnum;
use Homeapp\AuditBundle\ActivityData;
use Homeapp\AuditBundle\ActorInfoFetcherInterface;
use Homeapp\AuditBundle\Auditable;
use Homeapp\AuditBundle\ChangeSetInterface;
use Homeapp\AuditBundle\DatabaseStorage;
use Homeapp\AuditBundle\IdentifierExtractor;
use Homeapp\AuditBundle\RequestIdentifierInterface;

class DatabaseActivitySubscriber
{
    private DatabaseStorage $storage;
    private IdentifierExtractor $extractor;
    private RequestIdentifierInterface $requestIdentifier;
    private ActorInfoFetcherInterface $actorInfoFetcher;
    private ChangeSetInterface $changeSet;
    private Auditable $auditable;

    public function __construct(
        DatabaseStorage $storage,
        IdentifierExtractor $extractor,
        RequestIdentifierInterface $requestIdentifier,
        ActorInfoFetcherInterface $actorInfoFetcher,
        ChangeSetInterface $changeSet,
        Auditable $auditable
    ) {
        $this->storage = $storage;
        $this->extractor = $extractor;
        $this->requestIdentifier = $requestIdentifier;
        $this->actorInfoFetcher = $actorInfoFetcher;
        $this->changeSet = $changeSet;
        $this->auditable = $auditable;
    }

    /**
     * @throws MappingException
     */
    public function postPersist(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();
        if (!$this->auditable->isAuditable($entity)) {
            return;
        }
        $class = get_class($entity);
        $changeSet = $this->changeSet->forCreate($entity);
        $this->storage->insert(
            new ActivityData(
                $class,
                (string)$this->extractor->getIdentifier($entity),
                ActionTypeEnum::INSERT,
                $this->requestIdentifier->getRequestId(),
                $this->actorInfoFetcher->getId(),
                $this->actorInfoFetcher->getIp(),
                $changeSet
            )
        );
    }

    /**
     * @throws MappingException
     */
    public function preRemove(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();
        if (!$this->auditable->isAuditable($entity)) {
            return;
        }
        $class = get_class($entity);
        $changeSet = $this->changeSet->forCreate($entity);
        $this->storage->insert(
            new ActivityData(
                $class,
                (string)$this->extractor->getIdentifier($entity),
                ActionTypeEnum::DELETE,
                $this->requestIdentifier->getRequestId(),
                $this->actorInfoFetcher->getId(),
                $this->actorInfoFetcher->getIp(),
                $changeSet
            )
        );
    }

    /**
     * @throws MappingException
     */
    public function preUpdate(PreUpdateEventArgs $args): void
    {
        $entity = $args->getObject();
        if (!$this->auditable->isAuditable($entity)) {
            return;
        }
        $class = get_class($entity);
        $this->storage->insert(
            new ActivityData(
                $class,
                (string)$this->extractor->getIdentifier($entity),
                ActionTypeEnum::UPDATE,
                $this->requestIdentifier->getRequestId(),
                $this->actorInfoFetcher->getId(),
                $this->actorInfoFetcher->getIp(),
                $args->getEntityChangeSet(),
            )
        );
    }
}

<?php

declare(strict_types=1);

namespace Homeapp\AuditBundle;

use Homeapp\AuditBundle\Entity\Activity;

class Audit
{
    private StorageInterface $storage;
    /** @var array<class-string, bool>  */
    private array $entitiesToTract = [
        Activity::class
    ];
    private IdentifierExtractor $extractor;
    private ActorInfoFetcherInterface $actorInfoFetcher;
    private ChangeSetInterface $changeSet;
    private RequestIdentifierInterface $requestIdentifier;

    public function __construct(
        StorageInterface $storage,
        IdentifierExtractor $extractor,
        ActorInfoFetcherInterface $actorInfoFetcher,
        ChangeSetInterface $changeSet,
        RequestIdentifierInterface $requestIdentifier
    ) {
        $this->storage = $storage;
        $this->extractor = $extractor;
        $this->actorInfoFetcher = $actorInfoFetcher;
        $this->changeSet = $changeSet;
        $this->requestIdentifier = $requestIdentifier;
    }

    public function hold(ActivityData $activity): void
    {
        $this->storage->send($activity);
    }

    /**
     * @throws \Doctrine\ORM\Mapping\MappingException
     */
    public function custom(object $entity, string $actionType):void
    {
        $entityClass = get_class($entity);
        $this->hold(
            new ActivityData(
                $entityClass,
                $this->extractor->getIdentifier($entity),
                $actionType,
                $this->requestIdentifier->getRequestId(),
                $this->actorInfoFetcher->getId(),
                $this->actorInfoFetcher->getIp(),
                $this->changeSet->forCreate($entity)
            )
        );
    }


    /**
     * @throws \Doctrine\ORM\Mapping\MappingException
     */
    public function create(object $entity):void
    {
        $entityClass = get_class($entity);
        $this->hold(
            new ActivityData(
                $entityClass,
                $this->extractor->getIdentifier($entity),
                ActionTypeEnum::CREATE,
                $this->requestIdentifier->getRequestId(),
                $this->actorInfoFetcher->getId(),
                $this->actorInfoFetcher->getIp(),
                $this->changeSet->forCreate($entity)
            )
        );
    }

    /**
     * @throws \Doctrine\ORM\Mapping\MappingException
     */
    public function update(object $entity):void
    {
        $entityClass = get_class($entity);
        $this->hold(
            new ActivityData(
                $entityClass,
                $this->extractor->getIdentifier($entity),
                ActionTypeEnum::UPDATE,
                $this->requestIdentifier->getRequestId(),
                $this->actorInfoFetcher->getId(),
                $this->actorInfoFetcher->getIp(),
                $this->changeSet->forCreate($entity)
            )
        );
    }

    /** @param class-string $entity  */
    public function addEntityToTrack(string $entity):void
    {
        $this->entitiesToTract[$entity] = true;
    }
    /** @param class-string $entity  */
    public function isTracked(string $entity):bool
    {
        return array_key_exists($entity, $this->entitiesToTract);
    }
}

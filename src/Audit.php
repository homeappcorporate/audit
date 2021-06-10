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

    public function __construct(
        StorageInterface $storage,
        IdentifierExtractor $extractor,
        ActorInfoFetcherInterface $actorInfoFetcher,
        ChangeSetInterface $changeSet
    ) {
        $this->storage = $storage;
        $this->extractor = $extractor;
        $this->actorInfoFetcher = $actorInfoFetcher;
        $this->changeSet = $changeSet;
    }

    public function hold(ActivityData $activity): void
    {
        $this->storage->send($activity);
    }

    public function create(object $entity):void
    {
        $entityClass = get_class($entity);
        $this->hold(
            new ActivityData(
                $entityClass,
                $this->extractor->getIdentifier($entity),
                ActionTypeEnum::CREATE,
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

<?php

declare(strict_types=1);

namespace Homeapp\AuditBundle;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreFlushEventArgs;
use Homeapp\AuditBundle\Entity\Activity;

class EventListener
{
    private Audit $audit;
    private ActorInfoFetcherInterface $actorIdFetcher;
    private ChangeSetGenerator $changeSetGenerator;

    public function __construct(
        Audit $audit,
        ActorInfoFetcherInterface $actorIdFetcher,
        ChangeSetGenerator $changeSetGenerator
    ) {
        $this->audit = $audit;
        $this->actorIdFetcher = $actorIdFetcher;
        $this->changeSetGenerator = $changeSetGenerator;
    }

    public function prePersist(LifecycleEventArgs $event) : void
    {
        $entity = $event->getEntity();
        if ($entity instanceof Activity) {
            return;
        }
        $entityClass = get_class($entity);
        $meta = $event->getEntityManager()->getClassMetadata($entityClass);

        $identifier = $meta->getSingleIdentifierFieldName(); // TODO getIdentifierFieldNames
        $this->audit->hold(
            new ActivityData(
                $entityClass,
                $identifier,
                ActionTypeEnum::CREATE,
                $this->actorIdFetcher->getId(),
                $this->actorIdFetcher->getIp(),
                $this->changeSetGenerator->changeSet($entity)
            )
        );
    }

    public function preFlush(PreFlushEventArgs $args)
    {
//        $this->audit->sendDataToPersistenceStorage();
    }
}




<?php

declare(strict_types=1);

namespace Homeapp\AuditBundle;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreFlushEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Homeapp\AuditBundle\Entity\Activity;

class EventListener
{
    private Audit $audit;
    private ActorInfoFetcherInterface $actorIdFetcher;

    public function __construct(
        Audit $audit,
        ActorInfoFetcherInterface $actorIdFetcher
    ) {
        $this->audit = $audit;
        $this->actorIdFetcher = $actorIdFetcher;
    }

    private function getIdentifier(object $entity, EntityManager $em):string
    {
        $entityClass = get_class($entity);
        $meta = $em->getClassMetadata($entityClass);

        $identifier = $meta->getSingleIdentifierFieldName();
        return (function (string $identifier):string {
            return (string)$this->$identifier;
        })->call($entity, $identifier);
    }

    public function postPersist(LifecycleEventArgs $event) : void
    {
        $entity = $event->getEntity();
        if ($entity instanceof Activity) {
            return;
        }
        $entityClass = get_class($entity);
        $identifier = $this->getIdentifier($entity, $event->getEntityManager());
        $this->audit->hold(
            new ActivityData(
                $entityClass,
                $identifier,
                ActionTypeEnum::CREATE,
                $this->actorIdFetcher->getId(),
                $this->actorIdFetcher->getIp(),
                (function() {
                    return get_object_vars($this);
                })->call($entity)
            )
        );
    }

    public function preUpdate(PreUpdateEventArgs $event) : void
    {
        $entity = $event->getEntity();
        if ($entity instanceof Activity) {
            return;
        }
        $entityClass = get_class($entity);
        $identifier = $this->getIdentifier($entity, $event->getEntityManager());
        $this->audit->hold(
            new ActivityData(
                $entityClass,
                $identifier,
                ActionTypeEnum::UPDATE,
                $this->actorIdFetcher->getId(),
                $this->actorIdFetcher->getIp(),
                $event->getEntityChangeSet(),
            )
        );
    }

    public function preFlush(PreFlushEventArgs $args)
    {
//        $this->audit->sendDataToPersistenceStorage();
    }
}
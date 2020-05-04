<?php

namespace Opifer\EavBundle\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Opifer\EavBundle\Manager\EavManager;
use Opifer\EavBundle\Model\ValueInterface;
use Opifer\EavBundle\Model\ValueSetInterface;

/**
 * Empty Value Listener.
 *
 * Adds/removes empty values to/from the valueset, to avoid storing null values
 * inside the database
 */
class EmptyValueListener
{
    /** @var EavManager */
    protected $eavManager;

    /**
     * Constructor.
     */
    public function __construct(EavManager $eavManager)
    {
        $this->eavManager = $eavManager;
    }

    /**
     * Create empty values for non-persisted values.
     */
    public function postLoad(LifeCycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if ($entity instanceof ValueSetInterface && null !== $entity->getValues()) {
            $this->eavManager->replaceEmptyValues($entity);
        }
    }

    /**
     * Remove empty values after persisting, to avoid null 'Value' values in
     * the database.
     */
    public function postPersist(LifeCycleEventArgs $args)
    {
        $entity = $args->getEntity();
        $entityManager = $args->getEntityManager();

        if ($entity instanceof ValueInterface && $entity->isEmpty()) {
            $entityManager->remove($entity);
        }
    }
}

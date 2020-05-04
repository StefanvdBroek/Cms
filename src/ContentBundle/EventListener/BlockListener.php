<?php

namespace Opifer\ContentBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Opifer\ContentBundle\Block\BlockManager;
use Opifer\ContentBundle\Model\BlockInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Block Entity Listener.
 *
 * This class listens to Doctrine events and calls the matching event methods on
 * the Block Service.
 */
class BlockListener implements EventSubscriber
{
    /** @var ContainerInterface */
    private $container;

    /**
     * Constructor.
     *
     * Requires the complete container, to avoid circular references.
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @return array
     */
    public function getSubscribedEvents()
    {
        return [
            Events::postLoad,
            Events::prePersist,
            Events::postPersist,
            Events::preUpdate,
            Events::postUpdate,
            Events::preRemove,
            Events::postRemove,
        ];
    }

    /**
     * Get the service.
     *
     * @return \Opifer\ContentBundle\Block\BlockServiceInterface
     *
     * @throws \Exception
     */
    public function getService(LifecycleEventArgs $args)
    {
        $service = $args->getObject();

        if (!$service) {
            throw new \Exception('Please set a provider on the entity before persisting any media');
        }

        return $this->getBlockManager()->getService($service);
    }

    /**
     * @return BlockManager
     */
    protected function getBlockManager()
    {
        return $this->container->get('opifer.content.block_manager');
    }

    /**
     * Post Load handler.
     */
    public function postLoad(LifecycleEventArgs $args)
    {
        if ($args->getObject() instanceof BlockInterface) {
            $this->getService($args)->postLoad($args->getObject());
        }
    }

    /**
     * Pre persist handler.
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        if ($args->getObject() instanceof BlockInterface) {
            $this->getService($args)->prePersist($args->getObject());
        }
    }

    /**
     * Post persist handler.
     */
    public function postPersist(LifecycleEventArgs $args)
    {
        if ($args->getObject() instanceof BlockInterface) {
            $this->getService($args)->postPersist($args->getObject());
        }
    }

    /**
     * Pre update handler.
     */
    public function preUpdate(LifecycleEventArgs $args)
    {
        if ($args->getObject() instanceof BlockInterface) {
            $this->getService($args)->preUpdate($args->getObject());
        }
    }

    /**
     * Post update handler.
     */
    public function postUpdate(LifecycleEventArgs $args)
    {
        if ($args->getObject() instanceof BlockInterface) {
            $this->getService($args)->postUpdate($args->getObject());
        }
    }

    /**
     * Pre remove handler.
     */
    public function preRemove(LifecycleEventArgs $args)
    {
        if ($args->getObject() instanceof BlockInterface) {
            $this->getService($args)->preRemove($args->getObject());
        }
    }

    /**
     * Post remove handler.
     */
    public function postRemove(LifecycleEventArgs $args)
    {
        if ($args->getObject() instanceof BlockInterface) {
            $this->getService($args)->postRemove($args->getObject());
        }
    }
}

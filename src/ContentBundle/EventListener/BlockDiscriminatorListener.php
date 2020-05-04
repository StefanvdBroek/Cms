<?php

namespace Opifer\EavBundle\EventListener;

use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Opifer\ContentBundle\Block\BlockManager;

class BlockDiscriminatorListener
{
    /** @var BlockManager */
    protected $blockManager;

    /**
     * BlockDiscriminatorListener constructor.
     */
    public function __construct(BlockManager $blockManager)
    {
        $this->blockManager = $blockManager;
    }

    /**
     * loadClassMetadata event.
     *
     * Retrieves the discriminatorMap from the BlockManagar, so we can
     * add entities to the discriminatorMap without adjusting the annotations
     * in the Block entity.
     *
     * @return void
     */
    public function loadClassMetadata(LoadClassMetadataEventArgs $args)
    {
        $metadata = $args->getClassMetadata();
        if ('Opifer\\CmsBundle\\Entity\\Block' == $metadata->name) {
            $metadata->setDiscriminatorMap($this->getDiscriminatorMap());
        }
    }

    /**
     * Transforms the registered blocks into a discriminatorMap.
     *
     * @return array
     */
    public function getDiscriminatorMap()
    {
        $map = [];
        foreach ($this->blockManager->getValues() as $alias => $value) {
            $map[$alias] = $value->getEntity();
        }

        return $map;
    }
}

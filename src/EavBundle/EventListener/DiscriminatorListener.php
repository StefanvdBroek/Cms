<?php

namespace Opifer\EavBundle\EventListener;

use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Opifer\EavBundle\ValueProvider\Pool;

class DiscriminatorListener
{
    /** @var Pool */
    protected $pool;

    /**
     * Constructor.
     */
    public function __construct(Pool $pool)
    {
        $this->pool = $pool;
    }

    /**
     * loadClassMetadata event.
     *
     * Retrieves the discriminatorMap from the value provider pool, so we can
     * add entities to the discriminatorMap without adjusting the annotations
     * in the Value entity.
     *
     * @return void
     */
    public function loadClassMetadata(LoadClassMetadataEventArgs $args)
    {
        $metadata = $args->getClassMetadata();
        if ('Opifer\\EavBundle\\Entity\\Value' == $metadata->name) {
            $metadata->setDiscriminatorMap($this->getDiscriminatorMap());
        }
    }

    /**
     * Transforms the provider values into a discriminatorMap.
     *
     * @return array
     */
    public function getDiscriminatorMap()
    {
        $map = [];
        foreach ($this->pool->getValues() as $alias => $value) {
            $map[$alias.'value'] = $value->getEntity();
        }

        return $map;
    }
}

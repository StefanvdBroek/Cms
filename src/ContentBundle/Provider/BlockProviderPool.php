<?php

namespace Opifer\ContentBundle\Provider;

/**
 * This pool holds the services tagged with 'opifer.content.block_provider'.
 */
class BlockProviderPool
{
    /** @var BlockProviderInterface[] */
    protected $providers;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->providers = [];
    }

    /**
     * Adds all the providers, tagged with 'opifer.content.block_provider' to the
     * provider pool.
     *
     * @param string $alias
     */
    public function addProvider(BlockProviderInterface $provider, $alias)
    {
        $this->providers[$alias] = $provider;
    }

    /**
     * Get provider by its alias.
     *
     * @param string $alias
     *
     * @return BlockProviderInterface
     */
    public function getProvider($alias)
    {
        return $this->providers[$alias];
    }

    /**
     * Get all registered providers.
     *
     * @return BlockProviderInterface[]
     */
    public function getProviders()
    {
        return $this->providers;
    }
}

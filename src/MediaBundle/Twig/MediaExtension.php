<?php

namespace Opifer\MediaBundle\Twig;

use Opifer\MediaBundle\Model\Media;
use Opifer\MediaBundle\Provider\Pool;

class MediaExtension extends \Twig_Extension
{
    /**
     * @var Pool
     */
    private $pool;

    /**
     * Constructor.
     */
    public function __construct(Pool $pool)
    {
        $this->pool = $pool;
    }

    /**
     * {@inheritdoc}
     */
    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('media_source_url', [$this, 'sourceUrl']),
        ];
    }

    /**
     * Gets the source url of a media item.
     *
     * @param Media $media
     *
     * @return \Twig_Markup
     */
    public function sourceUrl($media)
    {
        return new \Twig_Markup(
            $this->pool->getProvider($media->getProvider())->getUrl($media),
            'utf8'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'opifer.media.twig.extension';
    }
}

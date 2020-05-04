<?php

namespace Opifer\CmsBundle\Event;

use Symfony\Component\EventDispatcher\Event;

class SitemapEvent extends Event
{
    /** @var array */
    protected $urls = [];

    /**
     * @param string $loc
     * @param string $changefreq
     * @param int    $priority
     */
    public function addUrl($loc, \DateTime $lastmod, $changefreq = 'daily', $priority = 1)
    {
        $this->urls[] = [
            'loc' => $loc,
            'lastmod' => $lastmod,
            'changefreq' => $changefreq,
            'priority' => $priority,
        ];
    }

    /**
     * @return array
     */
    public function getUrls()
    {
        return $this->urls;
    }

    public function setUrls(array $urls)
    {
        $this->urls = $urls;
    }
}

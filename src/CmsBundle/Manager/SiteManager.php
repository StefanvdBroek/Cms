<?php

namespace Opifer\CmsBundle\Manager;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Opifer\CmsBundle\Entity\Site;

/**
 * Site Manager.
 */
class SiteManager
{
    /**
     * @var EntityManager
     */
    protected $em;

    /** @var DomainManager */
    protected $domainManager;

    /**
     * @var EntityRepository
     */
    protected $repository;

    /**
     * @var Site
     */
    protected $site = null;

    /**
     * SiteManager constructor.
     */
    public function __construct(EntityManager $em, DomainManager $domainManager)
    {
        $this->em = $em;
        $this->domainManager = $domainManager;
    }

    /**
     * @return EntityRepository
     */
    public function getRepository()
    {
        if (null === $this->repository) {
            $this->repository = $this->em->getRepository(Site::class);
        }

        return $this->repository;
    }

    public function getSite()
    {
        if (null === $this->site) {
            $domain = $this->domainManager->getDomain();
            if ($domain) {
                $this->site = $this->getRepository()->find($domain->getSite());
            }
        }

        return $this->site;
    }
}

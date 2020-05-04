<?php

namespace Opifer\ContentBundle\Provider;

use Doctrine\ORM\EntityManagerInterface;
use Opifer\ContentBundle\Entity\Template;

class TemplateBlockProvider implements BlockProviderInterface
{
    /** @var EntityManagerInterface */
    protected $em;

    /**
     * Constructor.
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockOwner($id)
    {
        return $this->em->getRepository(Template::class)->find($id);
    }
}

<?php

namespace Opifer\ContentBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Opifer\ContentBundle\Provider\BlockProviderInterface;

/**
 * Class TemplateRepository.
 */
class TemplateRepository extends EntityRepository implements BlockProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function getBlockOwner($id)
    {
        return $this->find($id);
    }
}

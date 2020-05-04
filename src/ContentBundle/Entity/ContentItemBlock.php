<?php

namespace Opifer\ContentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ContentItem Block.
 *
 * @ORM\Entity
 */
class ContentItemBlock extends Block
{
    /**
     * @return string
     */
    public function getBlockType()
    {
        return 'content_item';
    }
}

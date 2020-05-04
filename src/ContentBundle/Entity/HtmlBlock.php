<?php

namespace Opifer\ContentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * HtmlBlock.
 *
 * @ORM\Entity
 */
class HtmlBlock extends Block
{
    /**
     * @return string
     */
    public function getBlockType()
    {
        return 'html';
    }
}

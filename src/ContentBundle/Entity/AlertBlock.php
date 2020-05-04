<?php

namespace Opifer\ContentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Alert Block.
 *
 * @ORM\Entity
 */
class AlertBlock extends Block
{
    /**
     * @return string
     */
    public function getBlockType()
    {
        return 'alert';
    }
}

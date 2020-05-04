<?php

namespace Opifer\ContentBundle\Block;

use Opifer\ContentBundle\Model\BlockInterface;

interface VisitorInterface
{
    public function visit(BlockInterface $block);
}

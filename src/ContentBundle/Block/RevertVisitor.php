<?php

namespace Opifer\ContentBundle\Block;

use Opifer\ContentBundle\Model\BlockInterface;

class RevertVisitor implements VisitorInterface
{
    /** @var int */
    protected $rootVersion;

    /** @var BlockManager */
    protected $blockManager;

    /**
     * @param int $rootVersion
     */
    public function __construct(BlockManager $blockManager, $rootVersion)
    {
        $this->blockManager = $blockManager;
        $this->rootVersion = $rootVersion;
    }

    public function visit(BlockInterface $block)
    {
        $this->blockManager->revertSingle($block, $this->rootVersion);
    }
}

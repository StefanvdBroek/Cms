<?php

namespace Opifer\ContentBundle\Block;

interface BlockOwnerInterface
{
    /**
     * @return BlockOwnerInterface|false
     */
    public function getSuper();

    /**
     * @return \DateTime
     */
    public function getUpdatedAt();

    public function setUpdatedAt(\DateTime $dateTime);
}

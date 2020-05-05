<?php

namespace Opifer\ContentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AvatarBlock.
 *
 * @ORM\Entity
 */
class AvatarBlock extends Block
{
    /**
     * @var <Content>
     *
     * @ORM\ManyToOne(targetEntity="Opifer\ContentBundle\Model\ContentInterface")
     * @ORM\JoinColumn(name="login_content_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     */
    protected $loginContentItem;

    /**
     * @var <Content>
     *
     * @ORM\ManyToOne(targetEntity="Opifer\ContentBundle\Model\ContentInterface")
     * @ORM\JoinColumn(name="registration_content_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     */
    protected $registrationContentItem;

    /**
     * Set login content.
     *
     * @param Content $loginContentItem
     */
    public function setLoginContentItem($loginContentItem)
    {
        $this->loginContentItem = $loginContentItem;

        return $this;
    }

    /**
     * Get login content item.
     *
     * @return Content
     */
    public function getLoginContentItem()
    {
        return $this->loginContentItem;
    }

    /**
     * Set registration content.
     *
     * @param Content $loginContentItem
     */
    public function setRegistrationContentItem($registrationContentItem)
    {
        $this->registrationContentItem = $registrationContentItem;

        return $this;
    }

    /**
     * Get registration content item.
     *
     * @return Content
     */
    public function getRegistrationContentItem()
    {
        return $this->registrationContentItem;
    }

    /**
     * @return string
     */
    public function getBlockType()
    {
        return 'avatar';
    }
}

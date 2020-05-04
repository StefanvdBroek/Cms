<?php

namespace Opifer\CmsBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Opifer\EavBundle\Model\Schema as BaseSchema;

/**
 * Schema.
 */
class Schema extends BaseSchema
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $objectClass;

    /**
     * @var ArrayCollection
     */
    protected $attributes;

    /**
     * @var ArrayCollection
     **/
    protected $allowedInAttributes;
}

<?php

namespace Opifer\CmsBundle\Entity;

use Opifer\RedirectBundle\Model\Redirect as BaseRedirect;

class Redirect extends BaseRedirect
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $origin;

    /**
     * @var string
     */
    protected $target;

    /**
     * @var bool
     */
    protected $permanent;
}

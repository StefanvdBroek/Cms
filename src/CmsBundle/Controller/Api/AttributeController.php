<?php

namespace Opifer\CmsBundle\Controller\Api;

use FOS\RestBundle\Controller\FOSRestController;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Opifer\CmsBundle\Entity\Attribute;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class AttributeController extends FOSRestController
{
    /**
     * @ParamConverter()
     * @ApiDoc()
     *
     * @return Attribute
     */
    public function getAttributeAction(Attribute $attribute)
    {
        return $attribute;
    }

    /**
     * @ApiDoc()
     * @ParamConverter("attribute", options={"mapping": {"attribute": "name"}})
     *
     * @return array
     */
    public function getAttributesOptionsAction(Attribute $attribute)
    {
        return $attribute->getOptions();
    }
}

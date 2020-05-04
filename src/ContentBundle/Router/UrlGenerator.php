<?php

namespace Opifer\ContentBundle\Router;

use Symfony\Component\Routing\Generator\UrlGenerator as BaseUrlGenerator;

/**
 * Content UrlGenerator.
 */
class UrlGenerator extends BaseUrlGenerator
{
    /**
     * {@inheritdoc}
     */
    public function generate($name, $parameters = [], $referenceType = self::ABSOLUTE_PATH)
    {
        $url = parent::generate($name, $parameters, $referenceType);

        if ('/index' == substr($url, -6)) {
            $url = rtrim($url, 'index');
        }

        return $url;
    }
}

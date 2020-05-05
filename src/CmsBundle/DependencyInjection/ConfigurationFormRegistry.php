<?php

namespace Opifer\CmsBundle\DependencyInjection;

use Opifer\CmsBundle\Form\Type\ConfigurationFormTypeInterface;

class ConfigurationFormRegistry
{
    /**
     * @var array
     */
    protected $forms = [];

    public function addForm(ConfigurationFormTypeInterface $form)
    {
        $this->forms[] = $form;
    }

    /**
     * @return array
     */
    public function getForms()
    {
        return $this->forms;
    }
}

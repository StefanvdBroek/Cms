<?php

namespace Opifer\EavBundle\ValueProvider;

use Symfony\Component\Form\FormBuilderInterface;

abstract class AbstractValueProvider
{
    protected $enabled = true;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
    }

    public function buildParametersForm(FormBuilderInterface $builder, array $options = null)
    {
    }

    public function getName()
    {
        $class = explode('\\', get_class($this));
        $class = end($class);

        return strtolower(str_replace('ValueProvider', '', $class));
    }

    public function getLabel()
    {
        return ucfirst($this->getName());
    }

    public function isEnabled()
    {
        return $this->enabled;
    }
}

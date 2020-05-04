<?php

namespace Opifer\EavBundle\ValueProvider;

use Opifer\EavBundle\Entity\BooleanValue;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Adds value functionality for boolean attributes to the list of available values.
 */
class BooleanValueProvider extends AbstractValueProvider implements ValueProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('value', CheckboxType::class, [
            'required' => ($options['attribute']->getRequired()) ? true : false,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getEntity()
    {
        return BooleanValue::class;
    }

    /**
     * {@inheritdoc}
     */
    public function getLabel()
    {
        return 'Checkbox';
    }
}

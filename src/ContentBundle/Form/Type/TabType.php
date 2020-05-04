<?php

namespace Opifer\ContentBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Tab Form Type.
 */
class TabType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('label', TextType::class, [
                'label' => 'label.tab_label',
                'attr' => [
                    'placeholder' => 'placeholder.tab_label',
                ],
            ])
            ->add('parameters', TextType::class, [
                'label' => 'label.tab_parameters',
                'attr' => [
                    'placeholder' => 'placeholder.tab_parameters',
                ],
            ])
            ->add('sort', HiddenType::class, [
                'label' => 'label.tab_sort',
                'attr' => [
                    'class' => 'sort-input',
                    'placeholder' => 'placeholder.tab_sort',
                ],
            ])
            ->add('key', HiddenType::class, [
                'label' => 'label.tab_key',
                'attr' => [
                    'placeholder' => 'placeholder.tab_key',
                ],
            ])
        ;
    }
}

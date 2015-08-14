<?php

namespace Opifer\ContentBundle\Block;

use Opifer\ContentBundle\Entity\ImageBlock;
use Opifer\ContentBundle\Model\BlockInterface;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class ImageBlockService
 *
 * @package Opifer\ContentBundle\Block
 */
class ImageBlockService extends AbstractBlockService implements BlockServiceInterface
{
    protected $view = 'OpiferContentBundle:Block:Content/image.html.twig';

    /**
     * {@inheritDoc}
     */
    public function getName(BlockInterface $block = null)
    {
        return 'Image';
    }

    /**
     * {@inheritdoc}
     */
    public function buildManageForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildManageForm($builder, $options);

        // Default panel
        $builder->add(
            $builder->create('default', 'form', ['virtual' => true])
                ->add('media', 'mediapicker', [
                    'required'  => false,
                    'multiple' => false,
                    'property' => 'name',
                    'class' => 'OpiferCmsBundle:Media',
                ])
        )->add(
            $builder->create('properties', 'form')
                ->add('id', 'text', ['attr' => ['help_text' => 'help.html_id']])
                ->add('extra_classes', 'text', ['attr' => ['help_text' => 'help.extra_classes']])
        );
    }

    /**
     * {@inheritDoc}
     */
    public function createBlock()
    {
        return new ImageBlock;
    }

}
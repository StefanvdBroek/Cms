<?php

namespace Opifer\ContentBundle\Block\Service;

use Opifer\CmsBundle\Form\Type\CKEditorType;
use Opifer\ContentBundle\Block\Tool\Tool;
use Opifer\ContentBundle\Block\Tool\ToolsetMemberInterface;
use Opifer\ContentBundle\Entity\Block;
use Opifer\ContentBundle\Entity\ModalBlock;
use Opifer\ContentBundle\Form\Type\StylesType;
use Opifer\ContentBundle\Model\BlockInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Modal Block Service.
 */
class ModalBlockService extends AbstractBlockService implements BlockServiceInterface, ToolsetMemberInterface
{
    /**
     * {@inheritdoc}
     */
    public function buildManageForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildManageForm($builder, $options);

        $builder->get('default')
            ->add('name', TextType::class, [
                'label' => 'label.name',
                'attr' => [
                    'help_text' => 'help.block_name',
                    'tag' => 'settings',
                ],
                'required' => false,
            ])
            ->add('title', TextType::class, [
                'label' => 'label.display_name',
                'attr' => [
                    'help_text' => 'help.block_display_name',
                    'tag' => 'settings',
                ],
                'required' => false,
            ])
            ->add('header', CKEditorType::class, [
                'label' => 'label.header',
                'attr' => [
                    'label_col' => 12,
                    'widget_col' => 12,
                ],
                'required' => false,
            ])
            ->add('value', CKEditorType::class, [
                'label' => 'label.body',
                'attr' => [
                    'label_col' => 12,
                    'widget_col' => 12,
                ],
                'required' => false,
            ])
            ->add('footer', CKEditorType::class, [
                'label' => 'label.footer',
                'attr' => [
                    'label_col' => 12,
                    'widget_col' => 12,
                ],
                'required' => false,
            ]);

        $builder->get('properties')
            ->add('id', TextType::class, ['attr' => ['help_text' => 'help.html_id'], 'required' => false])
            ->add('extra_classes', TextType::class, ['attr' => ['help_text' => 'help.extra_classes'], 'required' => false])
            ->add('backdrop', CheckboxType::class, [
                'label' => 'label.modal_backdrop',
                'attr' => [
                    'align_with_widget' => true,
                    'help_text' => 'help_text.modal_backdrop',
                ],
            ]);

        if (isset($this->config['styles']) && count($this->config['styles'])) {
            $builder->get('properties')
                ->add('styles', StylesType::class, [
                    'choices' => $this->config['styles'],
                ]);
        }

        if (isset($this->config['template']) && count($this->config['template'])) {
            $builder->get('properties')
                ->add('template', ChoiceType::class, [
                    'label' => 'label.template',
                    'placeholder' => 'placeholder.choice_optional',
                    'attr' => ['help_text' => 'help.block_template', 'tag' => 'styles'],
                    'choices' => $this->config['templates'],
                    'required' => false,
            ]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getManageFormTypeName()
    {
        return 'content';
    }

    /**
     * {@inheritdoc}
     */
    public function createBlock()
    {
        return new ModalBlock();
    }

    /**
     * {@inheritdoc}
     */
    public function getTool(BlockInterface $block = null)
    {
        $tool = new Tool($this->getName(), 'modal');

        $tool
            ->setIcon('web')
            ->setGroup(Tool::GROUP_CONTENT)
            ->setDescription('Modal window in popup style');

        return $tool;
    }

    /**
     * @param BlockInterface $block
     *
     * @return string
     */
    public function getDescription(BlockInterface $block = null)
    {
        return 'Modal Window in popup style';
    }
}

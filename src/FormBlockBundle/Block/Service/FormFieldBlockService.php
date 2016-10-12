<?php

namespace Opifer\FormBlockBundle\Block\Service;

use Braincrafted\Bundle\BootstrapBundle\Form\Type\BootstrapCollectionType;
use Opifer\ContentBundle\Entity\Block;
use Opifer\FormBlockBundle\Entity\FormFieldBlock;
use Opifer\FormBlockBundle\Entity\ChoiceFieldBlock;
use Opifer\FormBlockBundle\Form\Type\FormFieldValidationType;
use Opifer\ContentBundle\Block\BlockRenderer;
use Opifer\ContentBundle\Block\Service\AbstractBlockService;
use Opifer\ContentBundle\Block\Service\BlockServiceInterface;
use Opifer\ContentBundle\Block\Tool\Tool;
use Opifer\ContentBundle\Block\Tool\ToolsetMemberInterface;
use Opifer\ContentBundle\Model\BlockInterface;
use Opifer\FormBundle\Model\FormManager;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Opifer\ExpressionEngine\DoctrineExpressionEngine;
use Opifer\ExpressionEngine\Form\Type\ExpressionEngineType;
use Opifer\ExpressionEngine\Prototype\AndXPrototype;
use Opifer\ExpressionEngine\Prototype\Choice;
use Opifer\ExpressionEngine\Prototype\OrXPrototype;
use Opifer\ExpressionEngine\Prototype\Prototype;
use Opifer\ExpressionEngine\Prototype\PrototypeCollection;
use Opifer\ExpressionEngine\Prototype\SelectPrototype;

/**
 * Form Block Service.
 */
abstract class FormFieldBlockService extends AbstractBlockService implements BlockServiceInterface, ToolsetMemberInterface
{
    /** @var FormManager */
    protected $formManager;

    /** @var bool {@inheritdoc} */
    protected $esiEnabled = true;

    /**
     * @param BlockRenderer $blockRenderer
     * @param FormManager   $formManager
     * @param array         $config
     */
    public function __construct(BlockRenderer $blockRenderer, FormManager $formManager, array $config)
    {
        parent::__construct($blockRenderer, $config);

        $this->formManager = $formManager;
    }

    /**
     * {@inheritdoc}
     */
    public function buildManageForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildManageForm($builder, $options);

        $propertiesForm = $builder->create('properties', FormType::class, ['label' => false, 'attr' => ['widget_col' => 12]]);

        $propertiesForm
            ->add('label', TextType::class)
            ->add('name', TextType::class, [
                'attr' => [
                    'pattern' => '^[a-z_-]+$',
                    'help_text' => 'A unique identifier for this form field. [a-z_-]',
                ],
            ])
            ->add('helpText', TextareaType::class, [
                'required' => false,
            ])
            ->add('validation', BootstrapCollectionType::class, [
                'required' => false,
                'allow_add' => true,
                'allow_delete' => true,
                'type' => FormFieldValidationType::class,
            ])
            ->add('formula',TextAreaType::class, [
                'required' => false,
                'attr' => [
                    'help_text' => 'Fill the value of this field with a formula'
                ]
            ])
        ;

        $builder->add($propertiesForm);

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $block = $event->getData();
            $form = $event->getForm();

            $form->get('properties')->add('displayLogic', ExpressionEngineType::class, [
                'prototypes' => $this->getPrototypes($block),
                'attr' => [
                    'help_text' => 'Add conditional logic to this block when it should be displayed. Default becomes hidden when conditions apply.'
                ]
            ]);
        });
    }

    /**
     * @return \Opifer\ExpressionEngine\Prototype\Prototype[]
     */
    protected function getPrototypes(Block $block)
    {
        $collection = new PrototypeCollection([
            new OrXPrototype(),
            new AndXPrototype(),
        ]);

        $owner = $block->getOwner();

        foreach ($owner->getBlocks() as $member) {
            if ($member instanceof ChoiceFieldBlock) {
                $properties = $member->getProperties();
                $choices = [];
                foreach ($properties['options'] as $option) {
                    $choices[] = new Choice($option['key'], $option['value']);
                }
                $collection->add(new SelectPrototype($properties['label'], $properties['name'], $choices));
            }
        }

        return $collection->all();
    }

    public function getViewParameters(BlockInterface $block)
    {
        $parameters = [
            'block_service' => $this,
            'block' => $block,
        ];

        return $parameters;
    }
}

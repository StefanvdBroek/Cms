<?php

namespace Opifer\ContentBundle\Block\Service;

use Opifer\ContentBundle\Block\Tool\Tool;
use Opifer\ContentBundle\Block\Tool\ToolsetMemberInterface;
use Opifer\ContentBundle\Entity\Block;
use Opifer\ContentBundle\Entity\TabNavBlock;
use Opifer\ContentBundle\Form\Type\StylesType;
use Opifer\ContentBundle\Form\Type\TabType;
use Opifer\ContentBundle\Model\BlockInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Class TabNavBlock.
 */
class TabNavBlockService extends AbstractBlockService implements LayoutBlockServiceInterface, BlockServiceInterface, ToolsetMemberInterface
{
    /** @var int */
    protected $tabCount = 1;

    /**
     * {@inheritdoc}
     */
    public function buildManageForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildManageForm($builder, $options);

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            [$this, 'onPreSetData']
        );

        $builder->addEventListener(
            FormEvents::SUBMIT,
            [$this, 'onSubmit']
        );

        $builder->addEventListener(
            FormEvents::POST_SET_DATA,
            [$this, 'onPostSetData']
        );

        $builder->get('properties')
            ->add('template', ChoiceType::class, [
                    'label' => 'label.template',
                    'attr' => ['help_text' => 'help.block_template', 'tag' => 'styles'],
                    'choices' => $this->config['templates'],
                    'required' => true,
                    'constraints' => [
                        new NotBlank(),
                    ],
                ])
            ->add('tabs', CollectionType::class, [
                'label' => 'label.tabs',
                'allow_add' => true,
                'allow_delete' => true,
                'entry_type' => TabType::class,
                'sub_widget_col' => 8,
                'button_col' => 4,
                'attr' => [
                    'class' => 'sortable-tabnav',
                    'tag' => 'general',
                    'help_text' => 'help.tabs_add_tab',
                ],
                'entry_options' => ['attr' => ['style' => 'inline']],
            ])
            ->add('id', TextType::class, ['attr' => ['help_text' => 'help.html_id'], 'required' => false])
            ->add('extra_classes', TextType::class, ['attr' => ['help_text' => 'help.extra_classes'], 'required' => false]);

        if ($this->config['styles']) {
            $builder->get('properties')
                ->add('styles', StylesType::class, [
                    'choices' => $this->config['styles'],
                ]);
        }
    }

    public function onPreSetData(FormEvent $event)
    {
        // block
        $block = $event->getData();
        $this->normalizeTabs($block);

        $event->setData($block);
    }

    public function onPostSetData(FormEvent $event)
    {
        $block = $event->getData();
        $this->normalizeTabs($block);

        $event->setData($block);
    }

    public function onSubmit(FormEvent $event)
    {
        $block = $event->getData();
        $this->normalizeTabs($block);

        $event->setData($block);
    }

    private function normalizeTabs(BlockInterface $block)
    {
        $properties = $block->getProperties();

        if (isset($properties['tabs']) && count($properties['tabs'])) {
            if (isset($properties['tabs'][0]) && !is_array($properties['tabs'][0])) {
                $converted = [];
                $sort = count($properties['tabs']);
                foreach ($properties['tabs'] as $key => $value) {
                    $converted[] = ['label' => $value, 'key' => $key, 'sort' => $sort--];
                }

                $properties['tabs'] = $converted;
            }

            $maxKey = 0;
            $maxSort = 0;
            array_walk($properties['tabs'], function ($tab) use (&$maxKey, &$maxSort) {
                if (isset($tab['key']) && $tab['key'] > $maxKey) {
                    $maxKey = $tab['key'];
                }
                if (isset($tab['sort']) && $tab['sort'] > $maxSort) {
                    $maxSort = $tab['sort'];
                }
            });

            ++$maxKey;
            ++$maxSort;
            foreach ($properties['tabs'] as &$tab) {
                if (!isset($tab['key']) || null === $tab['key'] || '' === $tab['key']) {
                    $tab['key'] = $maxKey++;
                }
                if (!isset($tab['sort']) || null === $tab['sort'] || '' === $tab['sort']) {
                    $tab['sort'] = $maxSort++;
                }
            }

            uasort($properties['tabs'], function ($a, $b) {
                return $a['sort'] < $b['sort'] ? 1 : 0;
            });

            $block->setProperties($properties);
        }

        return $block;
    }

    public function load(BlockInterface $block)
    {
        parent::load($block);

        $this->normalizeTabs($block);
    }

    /**
     * {@inheritdoc}
     */
    public function getManageFormTypeName()
    {
        return 'layout';
    }

    /**
     * {@inheritdoc}
     */
    public function createBlock()
    {
        return new TabNavBlock();
    }

    /**
     * {@inheritdoc}
     */
    public function getTool(BlockInterface $block = null)
    {
        $tool = new Tool($this->getName(), 'tabnav');

        $tool->setIcon('tab')
            ->setGroup(Tool::GROUP_LAYOUT)
            ->setDescription('Inserts tab navigation to control tabbed content');

        return $tool;
    }

    /**
     * {@inheritdoc}
     */
    public function getName(BlockInterface $block = null)
    {
        return 'Tabs';
    }

    /**
     * {@inheritdoc}
     */
    public function getPlaceholders(BlockInterface $block = null)
    {
        $this->normalizeTabs($block);
        $tabs = $block->getTabs();

        $placeholders = [];

        if ($tabs && count($tabs)) {
            foreach ($tabs as $tab) {
                $placeholders[$tab['key']] = $tab['label'];
            }
        }

        return $placeholders;
    }

    /**
     * @param BlockInterface $block
     *
     * @return string
     */
    public function getDescription(BlockInterface $block = null)
    {
        return 'This inserts tab navigation to control tabbed content';
    }
}

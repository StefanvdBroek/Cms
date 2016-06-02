<?php

namespace Opifer\MailingListBundle\Form\Type;

use Opifer\MailingListBundle\Entity\MailingList;
use Opifer\MailingListBundle\Provider\ProviderPool;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * MailingListType Type.
 */
class MailingListType extends AbstractType
{
    /** @var ProviderPool */
    protected $pool;

    public function __construct(ProviderPool $pool)
    {
        $this->pool = $pool;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            array($this, 'onPreSetData')
        );

        $builder
            ->add('name', TextType::class, [
                'required' => true,
                'label' => 'label.name',
                'attr' => [
                    'placeholder' => 'placeholder.name',
                ],
            ])
            ->add('displayName', TextType::class, [
                'required' => true,
                'label' => 'label.display_name',
                'attr' => [
                    'placeholder' => 'placeholder.display_name',
                ],
            ])
            ->add('provider', ChoiceType::class, [
                'required' => false,
                'label' => 'label.provider',
                'choices' => [
                    'MailChimp' => 'mailchimp',
                    'Mailplus' => 'mailplus',
                ],
                'choices_as_values' => true,
                'attr' => [
                    'placeholder' => 'placeholder.provider',
                ],
            ])
        ;
    }

    public function onPreSetData(FormEvent $event)
    {
        /** @var MailingList $list */
        $list = $event->getData();
        $form = $event->getForm();

        if ($list->getProvider()) {
            $provider = $this->pool->getProvider($list->getProvider());
            $remoteLists = $provider->getRemoteLists();

            $lists = array();
            array_map(function ($list) use (&$lists) {
                $lists[$list['id']] = $list['name'];
            }, $remoteLists);

            $form->add('remoteListId', ChoiceType::class, [
                'required' => false,
                'choices' => $lists,
                'label' => 'label.remote_list',
            ]);
        }
    }
}

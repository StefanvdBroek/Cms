<?php

namespace Opifer\ContentBundle\Block\Service;

use Doctrine\Common\Collections\ArrayCollection;
use Opifer\ContentBundle\Block\BlockRenderer;
use Opifer\ContentBundle\Block\Tool\Tool;
use Opifer\ContentBundle\Block\Tool\ToolsetMemberInterface;
use Opifer\ContentBundle\Entity\DownloadsBlock;
use Opifer\ContentBundle\Model\BlockInterface;
use Opifer\MediaBundle\Form\Type\MediaPickerType;
use Opifer\MediaBundle\Model\MediaManager;
use Symfony\Component\DependencyInjection\ContainerInterface as Container;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * Video Block Service.
 */
class DownloadsBlockService extends AbstractBlockService implements BlockServiceInterface, ToolsetMemberInterface
{
    /** @var MediaManager */
    protected $mediaManager;

    /**
     * Constructor.
     */
    public function __construct(BlockRenderer $blockRenderer, array $config, MediaManager $mediaManager, Container $container)
    {
        parent::__construct($blockRenderer, $config);

        $this->container = $container;
        $this->mediaManager = $mediaManager;
    }

    /**
     * {@inheritdoc}
     */
    public function buildManageForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildManageForm($builder, $options);

        $builder->get('default')
            ->add('value', MediaPickerType::class, [
                'to_json' => true,
                'multiple' => true,
                'required' => false,
                'label' => 'label.content',
                'attr' => [
                    'help_text' => 'help.download_media',
                ],
            ]);
    }

    /**
     * Download media item.
     *
     * @param string $filename
     *
     * @return Response
     */
    public function downloadMediaAction($filename)
    {
        $media = $this->mediaManager->getRepository()->findOneByReference($filename);
        $provider = $this->container->get('opifer.media.provider.pool')->getProvider($media->getProvider());

        $mediaUrl = $provider->getUrl($media);

        $fileSystem = $provider->getFileSystem();
        $file = $fileSystem->read($media->getReference());

        $response = new Response();
        $response->headers->set('Content-type', $media->getContentType());
        $response->headers->set('Content-Disposition', sprintf('attachment; filename="%s"', basename($mediaUrl)));
        $response->setContent($file);

        return $response;
    }

    public function load(BlockInterface $block)
    {
        $ids = json_decode($block->getValue());

        if (empty($ids) || !count($ids)) {
            return;
        }

        $items = $this->mediaManager->getRepository()->findByIds($ids);

        uasort($items, function ($a, $b) use ($ids) {
            return array_search($a->getId(), $ids) > array_search($b->getId(), $ids);
        });

        if ($items) {
            $block->setItems(new ArrayCollection($items));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function createBlock()
    {
        return new DownloadsBlock();
    }

    /**
     * {@inheritdoc}
     */
    public function getTool(BlockInterface $block = null)
    {
        $tool = new Tool('Downloads', 'downloads');

        $tool->setIcon('file_download')
            ->setDescription('List of media items available for download');

        return $tool;
    }

    /**
     * @param BlockInterface $block
     *
     * @return string
     */
    public function getDescription(BlockInterface $block = null)
    {
        return 'List of media items available for download';
    }
}

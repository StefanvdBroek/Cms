<?php

namespace Opifer\ContentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Opifer\MediaBundle\Model\MediaInterface;

/**
 * ImageBlock
 *
 * @ORM\Entity
 */
class ImageBlock extends Block
{
    /**
     * @var MediaInterface
     *
     * @ORM\ManyToOne(targetEntity="Opifer\MediaBundle\Model\MediaInterface")
     * @ORM\JoinColumn(name="media_id", referencedColumnName="id", onDelete="SET NULL")
     */
    protected $media;

    /**
     * @return MediaInterface
     */
    public function getMedia()
    {
        return $this->media;
    }

    /**
     * @param MediaInterface $media
     *
     * @return $this
     */
    public function setMedia($media)
    {
        $this->media = $media;

        return $this;
    }

    /**
     * @return string
     */
    public function getBlockType()
    {
        return 'image';
    }
}
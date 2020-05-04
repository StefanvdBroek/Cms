<?php

namespace Opifer\ContentBundle\Designer;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\RouterInterface;

class TemplateSuite extends AbstractDesignSuite
{
    /**
     * @var EntityManager
     */
    protected $em;

    public function __construct(RouterInterface $router, EntityManagerInterface $em)
    {
        $this->router = $router;
        $this->em = $em;
    }

    /**
     * {@inheritdoc}
     */
    public function load($id = 0, $version = 1)
    {
        $this->subject = $this->em->getRepository('OpiferContentBundle:Template')->find($id);
        $this->version = $version;

        if (!$this->subject) {
            throw $this->createNotFoundException('No template found for id '.$id);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getTitle()
    {
        return $this->subject->getDisplayName();
    }

    /**
     * {@inheritdoc}
     */
    public function getCaption()
    {
        return 'base.template';
    }

    /**
     * {@inheritdoc}
     */
    public function getPropertiesUrl()
    {
        return '';
    }

    /**
     * {@inheritdoc}
     */
    public function getCancelUrl()
    {
        return $this->router->generate('opifer_content_template_index');
    }

    /**
     * {@inheritdoc}
     */
    public function getCanvasUrl()
    {
        return $this->router->generate('opifer_content_contenteditor_view', ['owner' => 'template', 'ownerId' => $this->subject->getId()]);
    }

    public function saveSubject()
    {
        $this->em->flush();

        return $this;
    }
}

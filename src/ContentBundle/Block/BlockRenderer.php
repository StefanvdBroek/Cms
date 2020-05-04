<?php

namespace Opifer\ContentBundle\Block;

use Opifer\ContentBundle\Model\BlockInterface;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Controller\ControllerReference;
use Symfony\Component\HttpKernel\Fragment\FragmentHandler;

class BlockRenderer
{
    /** @var EngineInterface */
    protected $templating;

    /** @var FragmentHandler */
    protected $fragmentHandler;

    /** @var RequestStack */
    protected $requestStack;

    /**
     * Constructor.
     */
    public function __construct(EngineInterface $templating, FragmentHandler $fragmentHandler, RequestStack $requestStack)
    {
        $this->templating = $templating;
        $this->fragmentHandler = $fragmentHandler;
        $this->requestStack = $requestStack;
    }

    /**
     * @param string $view
     * @param null   $response
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function render($view, array $parameters = [], $response = null)
    {
        return $this->templating->renderResponse($view, $parameters, $response);
    }

    /**
     * @return string|null
     */
    public function renderEsi(BlockInterface $block)
    {
        $reference = new ControllerReference(
            'OpiferContentBundle:Frontend/Block:view',
            ['id' => $block->getId()],
            $this->getRequest()->query->all()
        );

        return $this->fragmentHandler->render($reference, 'esi');
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Request|null
     */
    protected function getRequest()
    {
        return $this->requestStack->getCurrentRequest();
    }
}

<?php

namespace Opifer\ContentBundle\Block\Service;

use Opifer\ContentBundle\Block\BlockRenderer;
use Opifer\ContentBundle\Block\Tool\Tool;
use Opifer\ContentBundle\Block\Tool\ToolsetMemberInterface;
use Opifer\ContentBundle\Entity\SearchResultsBlock;
use Opifer\ContentBundle\Model\BlockInterface;
use Opifer\ContentBundle\Model\Content;
use Opifer\ContentBundle\Model\ContentManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * SearchResults Block Service.
 */
class SearchResultsBlockService extends AbstractBlockService implements BlockServiceInterface, ToolsetMemberInterface
{
    /** @var RequestStack */
    protected $requestStack;

    /** @var ContentManagerInterface */
    protected $contentManager;

    public function __construct(BlockRenderer $blockRenderer, ContentManagerInterface $contentManager, array $config)
    {
        parent::__construct($blockRenderer, $config);

        $this->contentManager = $contentManager;
    }

    /**
     * {@inheritdoc}
     */
    public function createBlock()
    {
        return new SearchResultsBlock();
    }

    /**
     * {@inheritdoc}
     */
    public function getViewParameters(BlockInterface $block)
    {
        $parameters = [
            'block_service' => $this,
            'block' => $block,
            'searchResults' => $this->getSearchResults(),
        ];

        return $parameters;
    }

    /**
     * Get the search results.
     *
     * @return Content[]
     */
    public function getSearchResults()
    {
        $term = $this->getRequest()->get('search', null);

        // Avoid querying ALL content when no search value is provided
        if (!$term) {
            return [];
        }

        $host = $this->getRequest()->getHost();
        $locale = $this->getRequest()->attributes->get('content')->getLocale();

        return $this->contentManager->getRepository()->search($term, $host, $locale);
    }

    /**
     * {@inheritdoc}
     */
    public function getTool(BlockInterface $block = null)
    {
        $tool = new Tool('Search results', 'search_results');

        $tool->setIcon('search')
            ->setDescription('Lists search results from a user query');

        return $tool;
    }

    public function setRequest(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    /**
     * @return Request|null
     */
    public function getRequest()
    {
        return $this->requestStack->getCurrentRequest();
    }

    /**
     * @param BlockInterface $block
     *
     * @return string
     */
    public function getDescription(BlockInterface $block = null)
    {
        return 'Lists search results from a user query';
    }
}

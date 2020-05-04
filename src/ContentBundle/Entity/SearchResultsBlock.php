<?php

namespace Opifer\ContentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SearchResultsBlock.
 *
 * @ORM\Entity
 */
class SearchResultsBlock extends Block
{
    /**
     * @return string
     */
    public function getBlockType()
    {
        return 'search_results';
    }
}

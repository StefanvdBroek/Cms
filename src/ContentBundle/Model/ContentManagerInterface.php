<?php

namespace Opifer\ContentBundle\Model;

interface ContentManagerInterface
{
    /**
     * Get the full class name of the Content entity.
     *
     * @return string
     */
    public function getClass();

    /**
     * Initialize the content entity.
     *
     * @return ContentInterface
     */
    public function initialize();

    /**
     * Save content.
     *
     * @return ContentInterface
     */
    public function save(ContentInterface $content);

    /**
     * Remove content.
     *
     * @param array|int $content
     */
    public function remove($content);

    /**
     * Find one content item by its slug.
     *
     * @param string $slug
     *
     * @throws \Doctrine\ORM\NoResultException if content is not found
     *
     * @return ContentInterface
     */
    public function findOneBySlug($slug);

    /**
     * Get repository.
     *
     * @return \Doctrine\ORM\EntityRepository
     */
    public function getRepository();
}

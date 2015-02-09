<?php

namespace ArthurHoaro\RssCruncherApiBundle\Handler;

use ArthurHoaro\RssCruncherApiBundle\Model\IEntity;

interface GenericHandlerInterface {
    /**
     * Get a Feed given the identifier
     *
     * @api
     *
     * @param mixed $id
     *
     * @return IEntity
     */
    public function get($id);

    /**
     * Create a new IEntity.
     *
     * @param array $parameters
     *
     * @return IEntity
     */
    public function post(array $parameters);

    /**
     * Edit a Feed, or create if not exist.
     *
     * @param IEntity $feed
     * @param array         $parameters
     *
     * @return IEntity
     */
    public function put(IEntity $feed, array $parameters);

    /**
     * Partially update a Feed.
     *
     * @param IEntity $feed
     * @param array         $parameters
     *
     * @return IEntity
     */
    public function patch(IEntity $feed, array $parameters);
}
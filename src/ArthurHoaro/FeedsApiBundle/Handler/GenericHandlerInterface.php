<?php

namespace ArthurHoaro\FeedsApiBundle\Handler;

interface GenericHandlerInterface {
    /**
     * Get a Feed given the identifier
     *
     * @api
     *
     * @param mixed $id
     *
     * @return IFeed
     */
    public function get($id);

    /**
     * Create a new IFeed.
     *
     * @param array $parameters
     *
     * @return IFeed
     */
    public function post(array $parameters);

    /**
     * Edit a Feed, or create if not exist.
     *
     * @param IFeed $feed
     * @param array         $parameters
     *
     * @return IFeed
     */
    public function put(IFeed $feed, array $parameters);

    /**
     * Partially update a Feed.
     *
     * @param IFeed $feed
     * @param array         $parameters
     *
     * @return IFeed
     */
    public function patch(IFeed $feed, array $parameters);
}
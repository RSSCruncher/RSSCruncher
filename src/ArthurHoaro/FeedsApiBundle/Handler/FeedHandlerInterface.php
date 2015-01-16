<?php

namespace ArthurHoaro\FeedsApiBundle\Handler;

use ArthurHoaro\FeedsApiBundle\Model\IFeed;

interface FeedHandlerInterface {
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
     * Get a list of Feed.
     *
     * @param int $limit the limit of the result
     * @param int $offset starting from the offset
     *
     * @return array
     */
    public function all($limit = 5, $offset = 0);

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
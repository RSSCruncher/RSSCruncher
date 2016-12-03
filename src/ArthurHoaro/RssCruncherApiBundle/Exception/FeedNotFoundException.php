<?php

namespace ArthurHoaro\RssCruncherApiBundle\Exception;


use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class FeedNotFoundException
 *
 * 404 HTTP exception
 *
 * @package ArthurHoaro\RssCruncherApiBundle\Exception
 */
class FeedNotFoundException extends HttpException {
    /**
     * @var int feedId
     */
    protected $feedId;

    public function __construct($feedId = false) {
        $this->feedId = $feedId;
        parent::__construct(404, 'Could not find the requested feed (FeedId #: "'. $this->feedId .'").');
    }

    /**
     * @return int feedId
     */
    public function getFeedId()
    {
        return $this->feedId;
    }


}

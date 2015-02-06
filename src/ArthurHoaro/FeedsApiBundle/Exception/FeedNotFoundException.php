<?php
/**
 * Created by PhpStorm.
 * User: ahoareau
 * Date: 05/02/2015
 * Time: 11:51
 */

namespace ArthurHoaro\FeedsApiBundle\Exception;


use Symfony\Component\HttpKernel\Exception\HttpException;

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
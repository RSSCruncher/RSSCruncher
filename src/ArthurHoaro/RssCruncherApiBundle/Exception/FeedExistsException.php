<?php

namespace ArthurHoaro\RssCruncherApiBundle\Exception;

use ArthurHoaro\RssCruncherApiBundle\ApiEntity\UserFeedDTO;
use ArthurHoaro\RssCruncherApiBundle\Entity\UserFeed;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class FeedExistsException
 * @package ArthurHoaro\RssCruncherApiBundle\Exception
 */
class FeedExistsException extends \Exception {

    /**
     * @var UserFeed
     */
    protected $userFeed;

    /**
     * FeedExistsException constructor.
     *
     * @param UserFeed $userFeed
     */
    public function __construct(UserFeed $userFeed) {
        $this->userFeed = $userFeed;
    }

    public function getFeed() : UserFeedDTO
    {
        $dto = new UserFeedDTO();
        $dto->setEntity($this->userFeed);
        return $dto;
    }
}

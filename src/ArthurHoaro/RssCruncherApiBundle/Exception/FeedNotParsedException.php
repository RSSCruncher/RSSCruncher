<?php

namespace ArthurHoaro\RssCruncherApiBundle\Exception;


use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class FeedNotParsedException
 *
 * 500 HTTP exception
 *
 * @package ArthurHoaro\RssCruncherApiBundle\Exception
 */
class FeedNotParsedException extends HttpException {
    public function __construct($feedUrl = null, \Exception $e = null) {
        parent::__construct(500, 'Could not parse Feed from URL '. $feedUrl .'.', $e);
    }
}

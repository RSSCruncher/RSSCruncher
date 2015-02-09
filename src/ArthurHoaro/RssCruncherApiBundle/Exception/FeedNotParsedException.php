<?php
/**
 * Created by PhpStorm.
 * User: ahoareau
 * Date: 05/02/2015
 * Time: 14:05
 */

namespace ArthurHoaro\RssCruncherApiBundle\Exception;


use Symfony\Component\HttpKernel\Exception\HttpException;

class FeedNotParsedException extends HttpException {
    public function __construct($feedUrl = null, \Exception $e = null) {
        parent::__construct(500, 'Could not parse Feed from URL '. $feedUrl .'.', $e);
    }
}
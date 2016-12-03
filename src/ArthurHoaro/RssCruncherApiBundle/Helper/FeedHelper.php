<?php

namespace ArthurHoaro\RssCruncherApiBundle\Helper;

/**
 * Class FeedHelper
 * @package ArthurHoaro\RssCruncherApiBundle\Helper
 */
class FeedHelper
{
    public static function cleanUrl($url) {
        return (new UrlCleaner($url))->cleanup();
    }
}

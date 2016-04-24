<?php
/**
 * FeedHelper.php
 * Author: arthur
 */

namespace ArthurHoaro\RssCruncherApiBundle\Helper;


class FeedHelper
{
    public static function cleanUrl($url) {
        return (new UrlCleaner($url))->cleanup();
    }
}
<?php
/**
 * StringUtil.php
 * Author: arthur
 */

namespace ArthurHoaro\RssCruncherApiBundle\Helper;


class StringUtil
{
    /**
     * Tells if a string start with a substring
     */
    public static function startsWith($haystack, $needle, $case=true)
    {
        if ($case) {
            return (strcmp(substr($haystack, 0, strlen($needle)), $needle) === 0);
        }
        return (strcasecmp(substr($haystack, 0, strlen($needle)), $needle) === 0);
    }

    /**
     * Tells if a string ends with a substring
     */
    public static function endsWith($haystack, $needle, $case=true)
    {
        if ($case) {
            return (strcmp(substr($haystack, strlen($haystack) - strlen($needle)), $needle) === 0);
        }
        return (strcasecmp(substr($haystack, strlen($haystack) - strlen($needle)), $needle) === 0);
    }
}
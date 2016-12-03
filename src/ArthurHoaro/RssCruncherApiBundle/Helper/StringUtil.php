<?php

namespace ArthurHoaro\RssCruncherApiBundle\Helper;

/**
 * Class StringUtil
 * @package ArthurHoaro\RssCruncherApiBundle\Helper
 */
class StringUtil
{
    /**
     * Tells if a string starts with a substring
     *
     * @param string $haystack The string to search in.
     * @param string $needle   Search for needle.
     * @param bool   $case     Case sensitive if true.
     *
     * @return bool true if starts with, false otherwise
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
     *
     * @param string $haystack The string to search in.
     * @param string $needle   Search for needle.
     * @param bool   $case     Case sensitive if true.
     *
     * @return bool true if ends with, false otherwise
     */
    public static function endsWith($haystack, $needle, $case=true)
    {
        if ($case) {
            return (strcmp(substr($haystack, strlen($haystack) - strlen($needle)), $needle) === 0);
        }
        return (strcasecmp(substr($haystack, strlen($haystack) - strlen($needle)), $needle) === 0);
    }
}

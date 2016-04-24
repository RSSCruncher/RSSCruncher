<?php
/**
 * UrlCleaner.php
 * Author: arthur
 */

namespace ArthurHoaro\RssCruncherApiBundle\Helper;



class UrlCleaner
{
    private static $annoyingQueryParams = array(
        // Facebook
        'action_object_map=',
        'action_ref_map=',
        'action_type_map=',
        'fb_',
        'fb=',

        // Scoop.it
        '__scoop',

        // Google Analytics & FeedProxy
        'utm_',

        // ATInternet
        'xtor='
    );

    private static $annoyingFragments = array(
        // ATInternet
        'xtor=RSS-',

        // Misc.
        'tk.rss_all'
    );

    /*
     * URL parts represented as an array
     *
     * @see http://php.net/parse_url
     */
    protected $parts;

    /**
     * Parses a string containing a URL
     *
     * @param string $url a string containing a URL
     */
    public function __construct($url)
    {
        $url = self::cleanupUnparsedUrl(trim($url));
        $this->parts = parse_url($url);

        if (!empty($url) && empty($this->parts['scheme'])) {
            $this->parts['scheme'] = 'http';
        }
    }

    /**
     * Clean up URL before it's parsed.
     * ie. handle urlencode, url prefixes, etc.
     *
     * @param string $url URL to clean.
     *
     * @return string cleaned URL.
     */
    protected static function cleanupUnparsedUrl($url)
    {
        return self::removeFirefoxAboutReader($url);
    }

    /**
     * Remove Firefox Reader prefix if it's present.
     *
     * @param string $input url
     *
     * @return string cleaned url
     */
    protected static function removeFirefoxAboutReader($input)
    {
        $firefoxPrefix = 'about://reader?url=';
        if (StringUtil::startsWith($input, $firefoxPrefix)) {
            return urldecode(ltrim($input, $firefoxPrefix));
        }
        return $input;
    }

    /**
     * Returns a string representation of this URL
     */
    public function toString($keepScheme)
    {
        $scheme   = isset($this->parts['scheme']) && $keepScheme ? $this->parts['scheme'].'://' : '';
        $host     = isset($this->parts['host']) ? $this->parts['host'] : '';
        $port     = isset($this->parts['port']) ? ':'.$this->parts['port'] : '';
        $user     = isset($this->parts['user']) ? $this->parts['user'] : '';
        $pass     = isset($this->parts['pass']) ? ':'.$this->parts['pass']  : '';
        $pass     = ($user || $pass) ? "$pass@" : '';
        $path     = isset($this->parts['path']) ? $this->parts['path'] : '';
        $query    = isset($this->parts['query']) ? '?'.$this->parts['query'] : '';
        $fragment = isset($this->parts['fragment']) ? '#'.$this->parts['fragment'] : '';

        return "$scheme$user$pass$host$port$path$query$fragment";
    }

    /**
     * Removes undesired query parameters
     */
    protected function cleanupQuery()
    {
        if (! isset($this->parts['query'])) {
            return;
        }

        $queryParams = explode('&', $this->parts['query']);

        foreach (self::$annoyingQueryParams as $annoying) {
            foreach ($queryParams as $param) {
                if (StringUtil::startsWith($param, $annoying)) {
                    $queryParams = array_diff($queryParams, array($param));
                    continue;
                }
            }
        }

        if (count($queryParams) == 0) {
            unset($this->parts['query']);
            return;
        }

        $this->parts['query'] = implode('&', $queryParams);
    }

    /**
     * Removes undesired fragments
     */
    protected function cleanupFragment()
    {
        if (! isset($this->parts['fragment'])) {
            return;
        }

        foreach (self::$annoyingFragments as $annoying) {
            if (StringUtil::startsWith($this->parts['fragment'], $annoying)) {
                unset($this->parts['fragment']);
                break;
            }
        }
    }

    /**
     * Removes undesired query parameters and fragments
     *
     * @return string the string representation of this URL after cleanup
     */
    public function cleanup($keepScheme = true)
    {
        $this->cleanupQuery();
        $this->cleanupFragment();
        return $this->toString($keepScheme);
    }

    /**
     * Get URL scheme.
     *
     * @return string the URL scheme or false if none is provided.
     */
    public function getScheme() {
        if (!isset($this->parts['scheme'])) {
            return false;
        }
        return $this->parts['scheme'];
    }

    /**
     * Test if the Url is an HTTP one.
     *
     * @return true is HTTP, false otherwise.
     */
    public function isHttp() {
        return strpos(strtolower($this->parts['scheme']), 'http') !== false;
    }

    /**
     * Test if the Url is an HTTP one.
     *
     * @return true is HTTP, false otherwise.
     */
    public function isHttps() {
        return strpos(strtolower($this->parts['scheme']), 'https') !== false;
    }
}
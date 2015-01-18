<?php
/**
 * IFeed.php
 * Author: arthur
 */

namespace ArthurHoaro\FeedsApiBundle\Model;

interface IFeed extends IEntity {

    /**
     * Set sitename
     *
     * @param string $sitename
     * @return Feed
     */
    public function setSitename($sitename);

    /**
     * Get sitename
     *
     * @return string
     */
    public function getSitename();

    /**
     * Set siteurl
     *
     * @param string $siteurl
     * @return Feed
     */
    public function setSiteurl($siteurl);

    /**
     * Get siteurl
     *
     * @return string
     */
    public function getSiteurl();

    /**
     * Set feedname
     *
     * @param string $feedname
     * @return Feed
     */
    public function setFeedname($feedname);

    /**
     * Get feedname
     *
     * @return string
     */
    public function getFeedname();

    /**
     * Set feedurl
     *
     * @param string $feedurl
     * @return Feed
     */
    public function setFeedurl($feedurl);

    /**
     * Get feedurl
     *
     * @return string
     */
    public function getFeedurl();
} 
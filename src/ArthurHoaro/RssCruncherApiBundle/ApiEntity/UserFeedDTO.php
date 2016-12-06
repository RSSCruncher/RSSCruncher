<?php

namespace ArthurHoaro\RssCruncherApiBundle\ApiEntity;

use ArthurHoaro\RssCruncherApiBundle\Entity\UserFeed;

/**
 * Class UserFeedDTO
 *
 * UserFeed serialized for REST API.
 *
 * @package ArthurHoaro\RssCruncherApiBundle\ApiEntity
 */
class UserFeedDTO implements IApiEntity
{
    /**
     * @var int Internal ID
     */
    protected $id;

    /**
     * @var string Custom site name set by the user.
     */
    protected $sitename;

    /**
     * @var string Custom site URL set by the user.
     */
    protected $siteurl;

    /**
     * @var string Custom feed name set by the user.
     */
    protected $feedname;

    /**
     * @var string Unique feed URL (from the feed table).
     */
    protected $feedurl;

    /**
     * @var bool Enabled.
     */
    protected $enabled;

    /**
     * @param UserFeed $userFeed
     *
     * @return UserFeedDTO
     */
    public function setEntity($userFeed) {
        $this->setId($userFeed->getId());
        $this->setSitename($userFeed->getSitename() );
        $this->setSiteurl($userFeed->getSiteurl());
        $this->setFeedname($userFeed->getFeedname());
        $scheme = $userFeed->getFeed()->isHttps() ? 'https' : 'http';
        $this->setFeedurl($scheme . '://' . $userFeed->getFeed()->getFeedurl());

        return $this;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getSitename()
    {
        return $this->sitename;
    }

    /**
     * @param string $sitename
     */
    public function setSitename($sitename)
    {
        $this->sitename = $sitename ? $sitename : "";
    }

    /**
     * @return string
     */
    public function getSiteurl()
    {
        return $this->siteurl;
    }

    /**
     * @param string $siteurl
     */
    public function setSiteurl($siteurl)
    {
        $this->siteurl = $siteurl ? $siteurl : "";
    }

    /**
     * @return string
     */
    public function getFeedname()
    {
        return $this->feedname;
    }

    /**
     * @param string $feedname
     */
    public function setFeedname($feedname)
    {
        $this->feedname = $feedname ? $feedname : "";
    }

    /**
     * @return string
     */
    public function getFeedurl()
    {
        return $this->feedurl;
    }

    /**
     * @param string $feedurl
     */
    public function setFeedurl($feedurl)
    {
        $this->feedurl = $feedurl;
    }
}

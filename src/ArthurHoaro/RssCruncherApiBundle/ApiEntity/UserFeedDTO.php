<?php

namespace ArthurHoaro\RssCruncherApiBundle\ApiEntity;

use ArthurHoaro\RssCruncherApiBundle\Entity\UserFeed;


class UserFeedDTO implements IApiEntity
{
    /**
     * @var integer
     */
    protected $id;

    /**
     * @var string
     */
    protected $sitename;

    /**
     * @var string
     */
    protected $siteurl;

    /**
     * @var string
     */
    protected $feedname;

    /**
     * @var boolean
     */
    protected $enabled = true;

    /**
     * @var string
     */
    protected $feedurl;

    /**
     * @param UserFeed $userFeed
     *
     * @return UserFeedDTO
     */
    public function setEntity($userFeed) {
        $this->setId($userFeed->getId());
        $this->setSitename($userFeed->getSitename());
        $this->setSiteurl($userFeed->getSiteurl());
        $this->setFeedname($userFeed->getFeedname());
        $this->setEnabled($userFeed->isEnabled());
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
        $this->sitename = $sitename;
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
        $this->siteurl = $siteurl;
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
        $this->feedname = $feedname;
    }

    /**
     * @return boolean
     */
    public function isEnabled()
    {
        return $this->enabled;
    }

    /**
     * @param boolean $enabled
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;
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
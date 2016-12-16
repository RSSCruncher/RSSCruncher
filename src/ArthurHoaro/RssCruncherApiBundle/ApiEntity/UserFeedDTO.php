<?php

namespace ArthurHoaro\RssCruncherApiBundle\ApiEntity;

use ArthurHoaro\RssCruncherApiBundle\Entity\FeedCategory;
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
    protected $siteName;

    /**
     * @var string Custom site URL set by the user.
     */
    protected $siteUrl;

    /**
     * @var string Custom feed name set by the user.
     */
    protected $feedName;

    /**
     * @var string Unique feed URL (from the feed table).
     */
    protected $feedUrl;

    /**
     * @var FeedGroupDTO
     */
    //protected $feedGroup;

    /**
     * @var string
     */
    protected $category;

    /**
     * @param UserFeed $userFeed
     *
     * @return UserFeedDTO
     */
    public function setEntity($userFeed) {
        $this->setId($userFeed->getId());
        $this->setSiteName($userFeed->getSiteName() );
        $this->setSiteUrl($userFeed->getSiteUrl());
        $this->setFeedName($userFeed->getFeedName());
        $scheme = $userFeed->getFeed()->isHttps() ? 'https' : 'http';
        $this->setFeedUrl($scheme . '://' . $userFeed->getFeed()->getFeedUrl());
        //$this->setFeedGroup((new FeedGroupDTO())->setEntity($userFeed->getFeedGroup()));
        $this->setCategory($userFeed->getCategory());

        return $this;
    }

    /**
     * Get the Id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the Id.
     *
     * @param int $id
     *
     * @return UserFeedDTO
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the SiteName.
     *
     * @return string
     */
    public function getSiteName()
    {
        return $this->siteName;
    }

    /**
     * Set the SiteName.
     *
     * @param string $siteName
     *
     * @return UserFeedDTO
     */
    public function setSiteName($siteName)
    {
        $this->siteName = $siteName ? $siteName : '';

        return $this;
    }

    /**
     * Get the SiteUrl.
     *
     * @return string
     */
    public function getSiteUrl()
    {
        return $this->siteUrl;
    }

    /**
     * Set the SiteUrl.
     *
     * @param string $siteUrl
     *
     * @return UserFeedDTO
     */
    public function setSiteUrl($siteUrl)
    {
        $this->siteUrl = $siteUrl ? $siteUrl : '';

        return $this;
    }

    /**
     * Get the FeedName.
     *
     * @return string
     */
    public function getFeedName()
    {
        return $this->feedName;
    }

    /**
     * Set the FeedName.
     *
     * @param string $feedName
     *
     * @return UserFeedDTO
     */
    public function setFeedName($feedName)
    {
        $this->feedName = $feedName ? $feedName : '';

        return $this;
    }

    /**
     * Get the FeedUrl.
     *
     * @return string
     */
    public function getFeedUrl()
    {
        return $this->feedUrl;
    }

    /**
     * Set the FeedUrl.
     *
     * @param string $feedUrl
     *
     * @return UserFeedDTO
     */
    public function setFeedUrl($feedUrl)
    {
        $this->feedUrl = $feedUrl ? $feedUrl : '';

        return $this;
    }

    /**
     * Get the FeedGroup.
     *
     * @return FeedGroupDTO
     */
    /*public function getFeedGroup()
    {
        return $this->feedGroup;
    }*/

    /**
     * Set the FeedGroup.
     *
     * @param FeedGroupDTO $feedGroup
     *
     * @return UserFeedDTO
     */
    /*public function setFeedGroup($feedGroup)
    {
        $this->feedGroup = $feedGroup ? $feedGroup : '';

        return $this;
    }*/

    /**
     * Get the Category.
     *
     * @return mixed
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set the Category.
     *
     * @param mixed $category
     *
     * @return UserFeedDTO
     */
    public function setCategory($category)
    {
        if ($category != null) {
            $this->category = $category->getName();
        } else {
            $this->category = '';
        }

        return $this;
    }
}

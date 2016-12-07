<?php

namespace ArthurHoaro\RssCruncherApiBundle\Entity;


use ArthurHoaro\RssCruncherApiBundle\Model\IEntity;
use ArthurHoaro\RssCruncherApiBundle\Validator\Constraints as CustomAssert;
use ArthurHoaro\RssCruncherApiBundle\Validator\Constraints\NullableUrl;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Exclude;

/**
 * A UserFeed is an association table between a Feed an a ProxyUser.
 *
 * A Feed is unique in the whole database: that's main idea behind this project.
 * So we use this association table to let the user set custom labels, etc.
 *
 * FIXME! A fetch date needs to be added here.
 * FIXME! Just an idea: actually link this object to User,
 * FIXME! to share labels between apps and add another table to link UserFeed and ProxyUser.
 *
 * @ORM\Entity(repositoryClass="ArthurHoaro\RssCruncherApiBundle\Entity\UserFeedRepository")
 * @ORM\Table(
 *     name="user_feed",
 *     uniqueConstraints={@ORM\UniqueConstraint(name="user_feed_unique", columns={"feed_id", "feedgroup_id"})}
 * )
 *
 * @ExclusionPolicy("none")
 */
class UserFeed implements IEntity
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="site_name", type="string", length=255, nullable=true)
     */
    protected $siteName;

    /**
     * @var string
     *
     * @ORM\Column(name="site_url", type="string", length=2000, nullable=true)
     *
     * @CustomAssert\NullableUrl()
     */
    protected $siteUrl;

    /**
     * @var string
     *
     * @ORM\Column(name="feed_name", type="string", length=255, nullable=true)
     */
    protected $feedName;

    /**
     * @var boolean
     *
     * @ORM\Column(name="enabled", type="boolean")
     */
    protected $enabled = true;

    /**
     * @var Feed
     *
     * @ORM\ManyToOne(targetEntity="Feed", inversedBy="userFeeds", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="feed_id", referencedColumnName="id")
     *
     * @Exclude
     */
    protected $feed;

    /**
     * @var FeedGroup
     *
     * @ORM\ManyToOne(targetEntity="FeedGroup", inversedBy="feeds", fetch="EXTRA_LAZY")
     *
     * @Exclude
     */
    protected $feedGroup;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_creation", type="datetime")
     */
    protected $dateCreation;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_modification", type="datetime", nullable=true)
     */
    protected $dateModification;

    function __construct()
    {
        $this->dateCreation = new \DateTime('now');
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getSiteName()
    {
        return $this->siteName;
    }

    /**
     * @param string $siteName
     */
    public function setSiteName($siteName)
    {
        $this->siteName = $siteName;
    }

    /**
     * @return string
     */
    public function getSiteUrl()
    {
        return $this->siteUrl;
    }

    /**
     * @param string $siteUrl
     */
    public function setSiteUrl($siteUrl)
    {
        $this->siteUrl = $siteUrl;
    }

    /**
     * @return string
     */
    public function getFeedName()
    {
        return $this->feedName;
    }

    /**
     * @param string $feedName
     */
    public function setFeedName($feedName)
    {
        $this->feedName = $feedName;
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
     * Get the FeedGroup.
     *
     * @return FeedGroup
     */
    public function getFeedGroup(): FeedGroup
    {
        return $this->feedGroup;
    }

    /**
     * Set the FeedGroup.
     *
     * @param FeedGroup $feedGroup
     */
    public function setFeedGroup(FeedGroup $feedGroup)
    {
        $this->feedGroup = $feedGroup;
    }

    /**
     * @return Feed
     */
    public function getFeed()
    {
        return $this->feed;
    }

    /**
     * @param Feed $feed
     */
    public function setFeed($feed)
    {
        $this->feed = $feed;
    }

    /**
     * @param mixed $dateCreation
     */
    public function setDateCreation($dateCreation)
    {
        $this->dateCreation = $dateCreation;
    }

    /**
     * @return mixed
     */
    public function getDateCreation()
    {
        return $this->dateCreation;
    }

    /**
     * @param mixed $dateModification
     */
    public function setDateModification($dateModification)
    {
        $this->dateModification = $dateModification;
    }

    /**
     * @return mixed
     */
    public function getDateModification()
    {
        return $this->dateModification;
    }
}

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
 * @ORM\Entity
 * @ORM\Table(
 *     name="user_feed",
 *     uniqueConstraints={@ORM\UniqueConstraint(name="user_feed_unique", columns={"feed_id", "feedgroup_id"})}
 * )
 * @ORM\Entity(repositoryClass="ArthurHoaro\RssCruncherApiBundle\Entity\UserFeedRepository")
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
     * @ORM\Column(name="sitename", type="string", length=255, nullable=true)
     */
    protected $sitename;

    /**
     * @var string
     *
     * @ORM\Column(name="siteurl", type="string", length=2000, nullable=true)
     *
     * @CustomAssert\NullableUrl()
     */
    protected $siteurl;

    /**
     * @var string
     *
     * @ORM\Column(name="feedname", type="string", length=255, nullable=true)
     */
    protected $feedname;

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
     * @var ProxyUser
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
     * @return ProxyUser
     */
    public function getProxyUser()
    {
        return $this->proxyUser;
    }

    /**
     * @param ProxyUser $proxyUser
     */
    public function setProxyUser($proxyUser)
    {
        $this->proxyUser = $proxyUser;
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

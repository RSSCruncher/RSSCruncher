<?php
/**
 * FeedUser.php
 * Author: arthur
 */

namespace ArthurHoaro\RssCruncherApiBundle\Entity;


use ArthurHoaro\RssCruncherApiBundle\Model\IEntity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Exclude;

/**
 * @ORM\Entity
 * @ORM\Table(name="user_feed")
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
     * @ORM\Column(name="sitename", type="string", length=255)
     */
    protected $sitename;

    /**
     * @var string
     *
     * @ORM\Column(name="siteurl", type="string", length=2000)
     *
     * @Assert\Url()
     */
    protected $siteurl;

    /**
     * @var string
     *
     * @ORM\Column(name="feedname", type="string", length=255)
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
     * @ORM\ManyToOne(targetEntity="ProxyUser", inversedBy="feeds", fetch="EXTRA_LAZY")
     *
     * @Exclude
     */
    protected $proxyUser;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_creation", type="datetime")
     */
    protected $dateCreation;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_modification", type="datetime")
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

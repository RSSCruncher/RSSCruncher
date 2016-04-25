<?php

namespace ArthurHoaro\RssCruncherApiBundle\Entity;

use ArthurHoaro\RssCruncherApiBundle\Model\IEntity;
use ArthurHoaro\RssCruncherClientBundle\Entity\Client;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Exclude;
use ArthurHoaro\RssCruncherApiBundle\Entity\ProxyUser;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Feed
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="ArthurHoaro\RssCruncherApiBundle\Entity\FeedRepository")
 *
 * @ExclusionPolicy("none")
 */
class Feed implements IEntity
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="feedurl", type="string", length=2000)
     *
     * @Assert\Url()
     */
    private $feedurl;

    /**
     * @var boolean
     *
     * @ORM\Column(name="https", type="boolean")
     */
    private $https;

    /**
     * @ORM\OneToMany(targetEntity="Article", mappedBy="feed", fetch="EXTRA_LAZY")
     *
     * @Exclude
     */
    protected $articles;

    /**
     * @ORM\OneToMany(targetEntity="UserFeed", mappedBy="feed", fetch="EXTRA_LAZY")
     *
     * @Exclude
     */
    protected $userFeeds;

    /**
     * @var boolean
     *
     * @ORM\Column(name="enabled", type="boolean")
     */
    protected $enabled;

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
        $this->enabled = true;
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
     * Set feedurl
     *
     * @param string $feedurl
     * @return Feed
     */
    public function setFeedurl($feedurl)
    {
        $this->feedurl = $feedurl;

        return $this;
    }

    /**
     * Get feedurl
     *
     * @return string 
     */
    public function getFeedurl()
    {
        return $this->feedurl;
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
     * @return Article[]
     */
    public function getArticles()
    {
        return $this->articles;
    }

    /**
     * @param Article[] $articles
     */
    public function setArticles($articles)
    {
        $this->articles = $articles;
    }

    /**
     * @return mixed
     */
    public function getUserFeeds()
    {
        return $this->userFeeds;
    }

    /**
     * @param mixed $userFeeds
     */
    public function setUserFeeds($userFeeds)
    {
        $this->userFeeds = $userFeeds;
    }

    /**
     * @return string
     */
    public function isHttps()
    {
        return $this->https;
    }

    /**
     * @param string $https
     */
    public function setHttps($https)
    {
        $this->https = $https;
    }

    /**
     * @param \DateTime $dateCreation
     */
    public function setDateCreation($dateCreation)
    {
        $this->dateCreation = $dateCreation;
    }

    /**
     * @return \DateTime
     */
    public function getDateCreation()
    {
        return $this->dateCreation;
    }
}

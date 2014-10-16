<?php

namespace ArthurHoaro\FeedsApiBundle\Entity;

use ArthurHoaro\FeedsApiBundle\Model\IFeed;
use Doctrine\ORM\Mapping as ORM;

/**
 * IFeed
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="ArthurHoaro\FeedsApiBundle\Entity\FeedRepository")
 */
class Feed implements IFeed
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
     * @ORM\Column(name="sitename", type="string", length=255)
     */
    private $sitename;

    /**
     * @var string
     *
     * @ORM\Column(name="siteurl", type="string", length=255)
     */
    private $siteurl;

    /**
     * @var string
     *
     * @ORM\Column(name="feedname", type="string", length=255)
     */
    private $feedname;

    /**
     * @var string
     *
     * @ORM\Column(name="feedurl", type="string", length=255)
     */
    private $feedurl;

    /**
     * @var boolean
     *
     * @ORM\Column(name="enabled", type="boolean")
     */
    private $enabled = true;

    /**
     * @var boolean
     *
     * @ORM\Column(name="banned", type="boolean")
     */
    private $banned = true;

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
     * Set sitename
     *
     * @param string $sitename
     * @return IFeed
     */
    public function setSitename($sitename)
    {
        $this->sitename = $sitename;

        return $this;
    }

    /**
     * Get sitename
     *
     * @return string 
     */
    public function getSitename()
    {
        return $this->sitename;
    }

    /**
     * Set siteurl
     *
     * @param string $siteurl
     * @return IFeed
     */
    public function setSiteurl($siteurl)
    {
        $this->siteurl = $siteurl;

        return $this;
    }

    /**
     * Get siteurl
     *
     * @return string 
     */
    public function getSiteurl()
    {
        return $this->siteurl;
    }

    /**
     * Set feedname
     *
     * @param string $feedname
     * @return IFeed
     */
    public function setFeedname($feedname)
    {
        $this->feedname = $feedname;

        return $this;
    }

    /**
     * Get feedname
     *
     * @return string 
     */
    public function getFeedname()
    {
        return $this->feedname;
    }

    /**
     * Set feedurl
     *
     * @param string $feedurl
     * @return IFeed
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
    public function isBanned()
    {
        return $this->banned;
    }

    /**
     * @param boolean $banned
     */
    public function setBanned($banned)
    {
        $this->banned = $banned;
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
}

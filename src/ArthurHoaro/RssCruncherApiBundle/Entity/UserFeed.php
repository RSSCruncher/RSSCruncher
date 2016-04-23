<?php
/**
 * FeedUser.php
 * Author: arthur
 */

namespace ArthurHoaro\RssCruncherApiBundle\Entity;


use ArthurHoaro\RssCruncherApiBundle\Model\IEntity;

class FeedUser implements IEntity
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
     * @ORM\Column(name="siteurl", type="string", length=2000)
     *
     * @Assert\Url()
     */
    private $siteurl;

    /**
     * @var string
     *
     * @ORM\Column(name="feedname", type="string", length=255)
     */
    private $feedname;

    /**
     * @var boolean
     *
     * @ORM\Column(name="enabled", type="boolean")
     */
    private $enabled = true;

    /**
     * @var ProxyUser[]
     *
     * @ORM\ManyToMany(targetEntity="ProxyUser", inversedBy="feeds", fetch="EXTRA_LAZY")
     *
     * @Exclude
     */
    protected $proxyUsers;

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
     * @return ProxyUser[]
     */
    public function getProxyUsers()
    {
        return $this->proxyUsers;
    }

    /**
     * @param ProxyUser[] $proxyUsers
     */
    public function setProxyUsers($proxyUsers)
    {
        $this->proxyUsers = $proxyUsers;
    }
}

<?php

namespace ArthurHoaro\RssCruncherClientBundle\Entity;

use ArthurHoaro\RssCruncherApiBundle\Model\IFeed;
use Doctrine\ORM\Mapping as ORM;
use FOS\OAuthServerBundle\Entity\Client as BaseClient;
use Symfony\Component\Validator\Constraints as Assert;
use ArthurHoaro\RssCruncherApiBundle\Entity\Feed;

/**
 * Client
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Client extends BaseClient
{
    public static $DEFAULT_GRANT_TYPES = ['token', 'refresh_token'];

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
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    protected $name;

    /**
     * @var Feed[]
     *
     * @ORM\ManyToMany(targetEntity="ArthurHoaro\RssCruncherApiBundle\Entity\Feed", mappedBy="clients", fetch="EXTRA_LAZY")
     */
    protected $feeds;
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
     * Set name
     *
     * @param string $name
     *
     * @return Client
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set first element of $redirectUris
     *
     * @param string $redirectUri
     */
    public function setRedirectUri($redirectUri)
    {
        parent::setRedirectUris([$redirectUri]);
    }

    /**
     * Set first element of $redirectUris
     *
     * @return string first redirectUri
     *
     * @Assert\Url()
     */
    public function getRedirectUri()
    {
        $uris = parent::getRedirectUris();
        return (count($uris)) ? $uris[0] : '';
    }

    /**
     * @param string $grantType grant type.
     */
    public function setAllowedGrantType($grantType)
    {
        parent::setAllowedGrantTypes(array_merge(self::$DEFAULT_GRANT_TYPES, [$grantType]));
    }

    /**
     * @return string
     *
     * @Assert\Choice(choices = {"client_credentials", "authorization_code"}, message = "Choose a valid grant type.")
     */
    public function getAllowedGrantType()
    {
        $types = array_values(array_diff(parent::getAllowedGrantTypes(), self::$DEFAULT_GRANT_TYPES));
        return (count($types)) ? $types[0] : '';
    }

    /**
     * @return \ArthurHoaro\RssCruncherApiBundle\Model\IFeed[]
     */
    public function getFeeds()
    {
        return $this->feeds;
    }

    /**
     * @param \ArthurHoaro\RssCruncherApiBundle\Model\IFeed[] $feeds
     */
    public function setFeeds($feeds)
    {
        $this->feeds = $feeds;
    }
}


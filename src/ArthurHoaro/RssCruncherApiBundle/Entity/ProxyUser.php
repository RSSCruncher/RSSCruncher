<?php
/**
 * ProxyUser.php
 * Author: arthur
 */

namespace ArthurHoaro\RssCruncherApiBundle\Entity;

use ArthurHoaro\RssCruncherApiBundle\Model\IEntity;
use Doctrine\ORM\Mapping as ORM;
use ArthurHoaro\RssCruncherApiBundle\Entity\User;
use ArthurHoaro\RssCruncherClientBundle\Entity\Client;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * A 'user' using the API can be either a client, or a real user through a client (different grant type).
 * This is an abstraction to this notion, to have a single User class.
 * UniqueEntity({"client_id", "user_id"})
 *
 * @ORM\Entity
 * @ORM\Table(name="proxy_user", indexes={@ORM\Index(name="client_user", columns={"client_id", "user_id"})})
 */
class ProxyUser implements IEntity
{
    public static $TYPE_CLIENT = 'CLIENT';
    public static $TYPE_USER   = 'USER';

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var Client
     *
     * @ORM\ManyToOne(targetEntity="ArthurHoaro\RssCruncherClientBundle\Entity\Client", inversedBy="proxyUsers")
     * @ORM\JoinColumn(name="client_id", referencedColumnName="id")
     */
    protected $client;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="ArthurHoaro\RssCruncherApiBundle\Entity\User", inversedBy="proxyUsers")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;

    /**
     * @var Feed[]
     *
     * @ORM\OneToMany(targetEntity="UserFeed", mappedBy="userProxy", fetch="EXTRA_LAZY")
     */
    protected $feeds;

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
     * @return mixed
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @param mixed $client
     */
    public function setClient($client)
    {
        $this->client = $client;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return Feed[]
     */
    public function getFeeds()
    {
        return $this->feeds;
    }

    /**
     * @param Feed[] $feeds
     */
    public function setFeeds($feeds)
    {
        $this->feeds = $feeds;
    }

    /**
     * @param Feed $feed
     */
    public function addFeed($feed)
    {
        $this->feeds[] = $feed;
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

    /**
     * @param \DateTime $dateModification
     */
    public function setDateModification($dateModification)
    {
        $this->dateModification = $dateModification;
    }

    /**
     * @return \DateTime
     */
    public function getDateModification()
    {
        return $this->dateModification;
    }
}

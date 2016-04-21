<?php
/**
 * ProxyUser.php
 * Author: arthur
 */

namespace ArthurHoaro\RssCruncherApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use ArthurHoaro\RssCruncherApiBundle\Entity\User;
use ArthurHoaro\RssCruncherClientBundle\Entity\Client;

/**
 * A 'user' using the API can be either a client, or a real user through a client (different grant type).
 * This is an abstraction to this notion, to have a single User class.
 * 
 * @ORM\Entity
 * @ORM\Table(name="proxy_user", indexes={@ORM\Index(name="client_user", columns={"client_id", "user_id"})})
 */
class ProxyUser
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
     * @ORM\OneToOne(targetEntity="ArthurHoaro\RssCruncherClientBundle\Entity\Client")
     * @ORM\JoinColumn(name="client_id", referencedColumnName="id")
     */
    protected $client;

    /**
     * @var User
     *
     * @ORM\OneToOne(targetEntity="ArthurHoaro\RssCruncherApiBundle\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;

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
}

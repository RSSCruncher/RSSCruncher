<?php


namespace ArthurHoaro\RssCruncherApiBundle\Entity;

use ArthurHoaro\RssCruncherApiBundle\Model\IEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Exclude;

/**
 * @ORM\Table()
 * @ORM\Entity()
 *
 * @ExclusionPolicy("none")
 */
class FeedGroup implements IEntity
{
    const DEFAULT = 'default';

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
     * @ORM\Column(name="name", type="string")
     */
    protected $name;

    /**
     * @var ProxyUser
     *
     * @ORM\OneToMany(targetEntity="ProxyUser", mappedBy="feedGroup", fetch="EXTRA_LAZY")
     */
    protected $proxyUsers;

    /**
     * @var UserFeed[]
     *
     * @ORM\OneToMany(targetEntity="UserFeed", mappedBy="feedGroup", fetch="EXTRA_LAZY")
     */
    protected $feeds;

    /**
     * FeedGroup constructor.
     */
    public function __construct() {
        $this->proxyUsers = new ArrayCollection();
        $this->feeds = new ArrayCollection();
    }

    public function update()
    {
        return $this;
    }

    /**
     * Get the Id.
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Set the Id.
     *
     * @param int $id
     */
    public function setId(int $id)
    {
        $this->id = $id;
    }

    /**
     * Get the Name.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Set the Name.
     *
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * Get the ProxyUsers.
     *
     * @return ProxyUser
     */
    public function getProxyUsers()
    {
        return $this->proxyUsers;
    }

    /**
     * Set the ProxyUsers.
     *
     * @param ProxyUser[] $proxyUsers
     *
     * @return FeedGroup
     */
    public function setProxyUsers($proxyUsers)
    {
        $this->proxyUsers = $proxyUsers;

        return $this;
    }

    /**
     * Add a ProxyUser.
     *
     * @param ProxyUser $proxyUser
     *
     * @return FeedGroup
     */
    public function addProxyUser($proxyUser)
    {
        $this->proxyUsers[] = $proxyUser;

        return $this;
    }


    /**
     * Get the UserFeeds.
     *
     * @return UserFeed[]
     */
    public function getUserFeeds(): array
    {
        return $this->feeds;
    }

    /**
     * Set the UserFeeds.
     *
     * @param UserFeed[] $userFeeds
     */
    public function setUserFeeds(array $userFeeds)
    {
        $this->feeds = $userFeeds;
    }

    /**
     * Add a UserFeed
     *
     * @param UserFeed $userFeed
     */
    public function addUserFeed(UserFeed $userFeed)
    {
        $this->feeds[] = $userFeed;
    }
}

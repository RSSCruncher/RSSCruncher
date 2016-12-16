<?php


namespace ArthurHoaro\RssCruncherApiBundle\Entity;

use ArthurHoaro\RssCruncherApiBundle\Model\IEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Exclude;

/**
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="ArthurHoaro\RssCruncherApiBundle\Entity\FeedCategoryRepository")
 *
 * @ExclusionPolicy("none")
 */
class FeedCategory implements IEntity
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
     * @var FeedGroup
     *
     * @ORM\ManyToOne(targetEntity="FeedGroup", inversedBy="feedCategories", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="feed_group_id", referencedColumnName="id")
     */
    protected $feedGroup;

    /**
     * @var UserFeed[]
     *
     * @ORM\OneToMany(targetEntity="UserFeed", mappedBy="category", fetch="EXTRA_LAZY")
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
     * @ORM\Column(name="date_modification", type="datetime", nullable=true)
     */
    protected $dateModification;

    /**
     * FeedGroup constructor.
     */
    public function __construct() {
        $this->feeds = new ArrayCollection();
        $this->dateCreation = new \DateTime();
    }

    public function update()
    {
        $this->dateModification = new \DateTime();
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
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the Name.
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Get the UserFeeds.
     *
     * @return UserFeed[]
     */
    public function getUserFeeds()
    {
        return $this->feeds;
    }

    /**
     * Set the UserFeeds.
     *
     * @param UserFeed[] $userFeeds
     */
    public function setUserFeeds($userFeeds)
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

    /**
     * Get the FeedGroup.
     *
     * @return FeedGroup
     */
    public function getFeedGroup()
    {
        return $this->feedGroup;
    }

    /**
     * Set the FeedGroup.
     *
     * @param FeedGroup $feedGroup
     *
     * @return FeedCategory
     */
    public function setFeedGroup($feedGroup)
    {
        $this->feedGroup = $feedGroup;

        return $this;
    }

    /**
     * Get the DateCreation.
     *
     * @return \DateTime
     */
    public function getDateCreation()
    {
        return $this->dateCreation;
    }

    /**
     * Set the DateCreation.
     *
     * @param \DateTime $dateCreation
     *
     * @return FeedCategory
     */
    public function setDateCreation($dateCreation)
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    /**
     * Get the DateModification.
     *
     * @return \DateTime
     */
    public function getDateModification()
    {
        return $this->dateModification;
    }

    /**
     * Set the DateModification.
     *
     * @param \DateTime $dateModification
     *
     * @return FeedCategory
     */
    public function setDateModification($dateModification)
    {
        $this->dateModification = $dateModification;

        return $this;
    }
}

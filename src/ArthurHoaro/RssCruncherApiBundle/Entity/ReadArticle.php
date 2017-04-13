<?php


namespace ArthurHoaro\RssCruncherApiBundle\Entity;


use ArthurHoaro\RssCruncherApiBundle\Model\IEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class ReadArticle
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="ArthurHoaro\RssCruncherApiBundle\Entity\ReadArticleRepository")
 *
 * @package ArthurHoaro\RssCruncherApiBundle\Entity
 */
class ReadArticle
{
    /**
     * @var Article
     *
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Article", inversedBy="readArticles", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="article_id", referencedColumnName="id")
     */
    protected $article;

    /**
     * @var UserFeed
     *
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="UserFeed", inversedBy="readArticles", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="user_feed_id", referencedColumnName="id")
     */
    protected $userFeed;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    protected $read;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created", type="datetime")
     */
    protected $readDate;

    public function __construct()
    {
        $this->readDate = new \DateTime();
    }

    public function update()
    {
    }

    /**
     * Get the Id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the Id.
     *
     * @param int $id
     *
     * @return ReadArticle
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the Article.
     *
     * @return Article
     */
    public function getArticle()
    {
        return $this->article;
    }

    /**
     * Set the Article.
     *
     * @param Article $article
     *
     * @return ReadArticle
     */
    public function setArticle($article)
    {
        $this->article = $article;

        return $this;
    }

    /**
     * Get the UserFeed.
     *
     * @return UserFeed
     */
    public function getUserFeed()
    {
        return $this->userFeed;
    }

    /**
     * Set the UserFeed.
     *
     * @param UserFeed $userFeed
     *
     * @return ReadArticle
     */
    public function setUserFeed($userFeed)
    {
        $this->userFeed = $userFeed;

        return $this;
    }

    /**
     * Get the Read.
     *
     * @return bool
     */
    public function isRead()
    {
        return $this->read;
    }

    /**
     * Set the Read.
     *
     * @param bool $read
     *
     * @return ReadArticle
     */
    public function setRead($read)
    {
        $this->read = $read;

        return $this;
    }

    /**
     * Get the ReadDate.
     *
     * @return \DateTime
     */
    public function getReadDate()
    {
        return $this->readDate;
    }

    /**
     * Set the ReadDate.
     *
     * @param \DateTime $readDate
     *
     * @return ReadArticle
     */
    public function setReadDate($readDate)
    {
        $this->readDate = $readDate;

        return $this;
    }
}

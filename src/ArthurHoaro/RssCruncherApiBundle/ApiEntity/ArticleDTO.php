<?php

namespace ArthurHoaro\RssCruncherApiBundle\ApiEntity;


use ArthurHoaro\RssCruncherApiBundle\Entity\Article;
use ArthurHoaro\RssCruncherApiBundle\Entity\UserFeed;

/**
 * Class ArticleDTO
 *
 * Article serialized for REST API.
 *
 * @package ArthurHoaro\RssCruncherApiBundle\ApiEntity
 */
class ArticleDTO implements IApiEntity
{
    /**
     * @var int Article internal ID.
     */
    protected $id;

    /**
     * @var string <ID> published in feed.
     */
    protected $publicId;

    /**
     * @var String Article title.
     */
    protected $title;

    /**
     * @var String Article URI.
     */
    protected $link;

    /**
     * @var String Latest article content.
     */
    protected $content;

    /**
     * FIXME! name + email
     * @var string Article author.
     */
    protected $author;

    /**
     * @var \DateTime Date of creation.
     */
    protected $publicationDate;

    /**
     * @var \DateTime Latest update.
     */
    protected $modificationDate;

    /**
     * @var UserFeedDTO UserFeed attached (to get the custom feed name + uri)
     */
    protected $feed;

    // WTF??? reflection fatal error without this...
    public $userFeed = null;

    /**
     * Create an ArticleDTO using an Article entity.
     *
     * @param Article  $entity
     * @param UserFeed $feed
     *
     * @return ArticleDTO
     */
    public function setEntity($entity, $feed = null)
    {
        $this->setId($entity->getId());
        $this->setPublicId($entity->getPublicId());
        $this->setTitle($entity->getTitle());
        $this->setLink($entity->getLink());
        $this->setContent($entity->getLastArticleContent()->getContent());
        $this->setAuthor($entity->getAuthorEmail());
        $this->setPublicationDate($entity->getPublicationDate());
        $this->setModificationDate($entity->getModificationDate());

        if ($feed != null) {
            $this->setFeed((new UserFeedDTO())->setEntity($feed));
        } else {
            $dto = new UserFeedDTO();
            $dto->setFeedurl($entity->getFeed()->getFeedurl());
            $this->setFeed($dto);
        }

        return $this;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getPublicId()
    {
        return $this->publicId;
    }

    /**
     * @param string $publicId
     */
    public function setPublicId($publicId)
    {
        $this->publicId = $publicId;
    }

    /**
     * @return String
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param String $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return String
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * @param String $link
     */
    public function setLink($link)
    {
        $this->link = $link;
    }

    /**
     * @return String
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param String $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * @return string
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @param string $author
     */
    public function setAuthor($author)
    {
        $this->author = $author;
    }

    /**
     * @return \DateTime
     */
    public function getPublicationDate()
    {
        return $this->publicationDate;
    }

    /**
     * @param \DateTime $publicationDate
     */
    public function setPublicationDate($publicationDate)
    {
        $this->publicationDate = $publicationDate;
    }

    /**
     * @return \DateTime
     */
    public function getModificationDate()
    {
        return $this->modificationDate;
    }

    /**
     * @param \DateTime $modificationDate
     */
    public function setModificationDate($modificationDate)
    {
        $this->modificationDate = $modificationDate;
    }

    /**
     * @return UserFeedDTO
     */
    public function getFeed()
    {
        return $this->feed;
    }

    /**
     * @param UserFeedDTO $feed
     */
    public function setFeed($feed)
    {
        $this->feed = $feed;
    }
}

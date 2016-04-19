<?php

namespace ArthurHoaro\RssCruncherApiBundle\Model;

use Doctrine\ORM\Mapping as ORM;

interface IArticle extends IEntity
{
    /**
     * Get public id
     *
     * @return int
     */
    public function getPublicId();

    /**
     * Set public id
     *
     * @param int $publicId
     */
    public function setPublicId($publicId);

    /**
     * Set title
     *
     * @param string $title
     * @return IArticles
     */
    public function setTitle($title);

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle();

    /**
     * Set publicationDate
     *
     * @param \DateTime $publicationDate
     * @return IArticle
     */
    public function setPublicationDate($publicationDate);

    /**
     * Get publicationDate
     *
     * @return \DateTime
     */
    public function getPublicationDate();

    /**
     * Set modificationDate
     *
     * @param \DateTime $modificationDate
     * @return IArticle
     */
    public function setModificationDate($modificationDate);

    /**
     * Get modificationDate
     *
     * @return \DateTime
     */
    public function getModificationDate();

    /**
     * Set summary
     *
     * @param string $summary
     * @return IArticle
     */
    public function setSummary($summary);

    /**
     * Get summary
     *
     * @return string
     */
    public function getSummary();

    /**
     * Set content
     *
     * @param string $content
     * @return IArticle
     */
    public function setContent($content);

    /**
     * Get content
     *
     * @return string
     */
    public function getContent();

    /**
     * Set authorName
     *
     * @param string $authorName
     * @return IArticle
     */
    public function setAuthorName($authorName);

    /**
     * Get authorName
     *
     * @return string
     */
    public function getAuthorName();

    /**
     * Set authorEmail
     *
     * @param string $authorEmail
     * @return IArticle
     */
    public function setAuthorEmail($authorEmail);

    /**
     * Get authorEmail
     *
     * @return string
     */
    public function getAuthorEmail();

    /**
     * Set link
     *
     * @param string $link
     * @return IArticle
     */
    public function setLink($link);

    /**
     * Get link
     *
     * @return string
     */
    public function getLink();

    /**
     * @return mixed
     */
    public function getFeed();

    /**
     * @param mixed $feed
     */
    public function setFeed($feed);

    /**
     * @return integer
     */
    public function getFeedId();
}

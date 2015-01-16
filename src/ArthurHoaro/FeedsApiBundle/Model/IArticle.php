<?php

namespace ArthurHoaro\FeedsApiBundle\Model;

use Doctrine\ORM\Mapping as ORM;

interface IArticle
{
    /**
     * Get id
     *
     * @return integer
     */
    public function getId();

    /**
     * Set title
     *
     * @param string $title
     * @return Article
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
     * @return Article
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
     * @return Article
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
     * @return Article
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
     * @return Article
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
     * @return Article
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
     * @return Article
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
     * @return Article
     */
    public function setLink($link);

    /**
     * Get link
     *
     * @return string
     */
    public function getLink();
}

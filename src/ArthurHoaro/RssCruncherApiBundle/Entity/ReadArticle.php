<?php


namespace ArthurHoaro\RssCruncherApiBundle\Entity;


use ArthurHoaro\RssCruncherApiBundle\Model\IEntity;

class ReadArticle implements IEntity
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var Article
     *
     * @ORM\Column(
     */
    protected $article;

    protected $userFeed;

    protected $read;

    public function update()
    {
        // TODO: Implement update() method.
    }

    public function getId()
    {
        // TODO: Implement getId() method.
    }


}
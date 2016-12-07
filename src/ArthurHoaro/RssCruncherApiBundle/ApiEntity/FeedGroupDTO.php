<?php


namespace ArthurHoaro\RssCruncherApiBundle\ApiEntity;


use ArthurHoaro\RssCruncherApiBundle\Entity\FeedGroup;

class FeedGroupDTO implements IApiEntity
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $name;

    /**
     * @param FeedGroup $entity
     *
     * @return FeedGroupDTO $this
     */
    public function setEntity($entity)
    {
        $this->setId($entity->getId());
        $this->setName($entity->getName());

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
}

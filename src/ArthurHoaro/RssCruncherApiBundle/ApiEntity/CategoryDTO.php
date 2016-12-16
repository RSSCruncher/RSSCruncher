<?php


namespace ArthurHoaro\RssCruncherApiBundle\ApiEntity;


use ArthurHoaro\RssCruncherApiBundle\Entity\FeedCategory;

class CategoryDTO implements IApiEntity
{
    protected $id;

    protected $name;

    protected $created;

    protected $updated;

    /**
     * @param FeedCategory $entity
     *
     * @return CategoryDTO
     */
    public function setEntity($entity)
    {
        $this->setId($entity->getId());
        $this->setName($entity->getName());
        $this->setCreated($entity->getDateCreation());
        $this->setUpdated($entity->getDateModification());

        return $this;
    }

    /**
     * Get the Id.
     *
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the Id.
     *
     * @param mixed $id
     *
     * @return CategoryDTO
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the Name.
     *
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the Name.
     *
     * @param mixed $name
     *
     * @return CategoryDTO
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the Created.
     *
     * @return mixed
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set the Created.
     *
     * @param mixed $created
     *
     * @return CategoryDTO
     */
    public function setCreated($created)
    {
        $this->created = $created->format(\DateTime::ISO8601);

        return $this;
    }

    /**
     * Get the Edited.
     *
     * @return mixed
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * Set the Edited.
     *
     * @param mixed $updated
     *
     * @return CategoryDTO
     */
    public function setUpdated($updated)
    {
        if ($updated != null) {
            $this->updated = $updated->format(\DateTime::ISO8601);
        } else {
            $this->updated = "";
        }

        return $this;
    }
}

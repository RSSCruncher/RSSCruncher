<?php


namespace ArthurHoaro\RssCruncherApiBundle\Exception;


use ArthurHoaro\RssCruncherApiBundle\ApiEntity\IApiEntity;
use ArthurHoaro\RssCruncherApiBundle\Model\IEntity;

class EntityExistsException extends \Exception {

    /**
     * @var IEntity
     */
    protected $entity;

    /**
     * @var IApiEntity
     */
    protected $apiEntityClass;

    /**
     * FeedExistsException constructor.
     *
     * @param IEntity $entity
     * @param string  $apiEntityClass
     */
    public function __construct(IEntity $entity, string $apiEntityClass) {
        $this->entity = $entity;
        $this->apiEntityClass = $apiEntityClass;
    }

    public function getApiEntity() : IApiEntity
    {
        $dto = new $this->apiEntityClass();
        $dto->setEntity($this->entity);
        return $dto;
    }
}
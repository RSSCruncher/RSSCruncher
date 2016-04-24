<?php

namespace ArthurHoaro\RssCruncherApiBundle\Handler;

use ArthurHoaro\RssCruncherApiBundle\Exception\InvalidFormException;
use ArthurHoaro\RssCruncherApiBundle\Form\EntityType;
use ArthurHoaro\RssCruncherApiBundle\Model\IEntity;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\FormFactoryInterface;
use Doctrine\Common\Persistence\ObjectRepository;

class GenericHandler implements GenericHandlerInterface {

    /**
     * @var ObjectManager
     */
    protected $om;

    /**
     * @var IEntity
     */
    protected $entityClass;

    /**
     * @var ObjectRepository
     */
    protected $repository;

    /**
     * @var FormFactoryInterface
     */
    protected $formFactory;

    /**
     * @var string
     */
    protected $formTypeclass;

    /**
     * GenericHandler constructor.
     *
     * @param ObjectManager        $om
     * @param IEntity              $entityClass
     * @param FormFactoryInterface $formFactory
     * @param string               $formTypeClass
     */
    public function __construct($om, $entityClass, $formFactory, $formTypeClass)
    {
        $this->om = $om;
        $this->entityClass = $entityClass;
        $this->repository = $this->om->getRepository($this->entityClass);
        $this->formFactory = $formFactory;
        $this->formTypeclass = $formTypeClass;
    }

    /**
     * Get a Entity.
     *
     * @param mixed $id
     *
     * @return IEntity
     */
    public function get($id)
    {
        return $this->repository->find($id);
    }

    /**
     * Get a list of Entity.
     *
     * @param int $limit the limit of the result
     * @param int $offset starting from the offset
     *
     * @return array
     */
    public function all($limit = 5, $offset = 0)
    {
        return $this->repository->findBy(array(), null, $limit, $offset);
    }

    /**
     * Create a new IEntity.
     *
     * @param array $parameters
     *
     * @return IEntity
     */
    public function post(array $parameters)
    {
        // Process form does all the magic, validate and hydrate the Entity Object.
        $entity = $this->processForm($this->create(), $parameters, 'POST');
        $this->om->persist($entity);
        $this->om->flush();

        return $entity;
    }

    /**
     * Edit a Entity, or create if not exist.
     *
     * @param IEntity $entity
     * @param array         $parameters
     *
     * @return IEntity
     */
    public function put(IEntity $entity, array $parameters)
    {
        return $this->processForm($entity, $parameters, 'PUT');
    }

    /**
     * Partially update a Entity.
     *
     * @param IEntity $entity
     * @param array         $parameters
     *
     * @return IEntity
     */
    public function patch(IEntity $entity, array $parameters)
    {
        return $this->processForm($entity, $parameters, 'PATCH');
    }

    /**
     * Processes the form.
     *
     * @param IEntity $entity
     * @param array $parameters
     * @param String $method
     * @param bool $formType
     *
     * @return IEntity
     */
    protected function processForm(IEntity $entity, array $parameters, $method = "PUT", $formType = false)
    {
        $formType = ($formType === false) ? $this->formTypeclass : $formType;
        $form = $this->formFactory->create($formType, $entity, array('method' => $method));
        $form->submit($parameters, 'PATCH' !== $method);
        if ($form->isValid()) {
            return $entity = $form->getData();
        }

        throw new InvalidFormException('Invalid submitted data', $form);
    }

    protected function create()
    {
        return new $this->entityClass();
    }
} 
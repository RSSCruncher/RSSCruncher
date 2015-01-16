<?php

namespace ArthurHoaro\FeedsApiBundle\Handler;

use ArthurHoaro\FeedsApiBundle\Exception\InvalidFormException;
use ArthurHoaro\FeedsApiBundle\Form\FeedType;
use ArthurHoaro\FeedsApiBundle\Model\IFeed;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\FormFactoryInterface;

class ArticleHandler implements ArticleHandlerInterface {

    private $formFactory;

    public function __construct(ObjectManager $om, $entityClass, FormFactoryInterface $formFactory)
    {
        $this->om = $om;
        $this->entityClass = $entityClass;
        $this->repository = $this->om->getRepository($this->entityClass);
        $this->formFactory = $formFactory;
    }

    /**
     * Get a Feed.
     *
     * @param mixed $id
     *
     * @return IFeed
     */
    public function get($id)
    {
        return $this->repository->find($id);
    }

    /**
     * Get a list of Feed.
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
     * Create a new IFeed.
     *
     * @param array $parameters
     *
     * @return IFeed
     */
    public function post(array $parameters)
    {
        $feed = $this->createFeed(); // factory method create an empty Feed

        // Process form does all the magic, validate and hydrate the Feed Object.
        return $this->processForm($feed, $parameters, 'POST');
    }

    /**
     * Edit a Feed, or create if not exist.
     *
     * @param IFeed $feed
     * @param array         $parameters
     *
     * @return IFeed
     */
    public function put(IFeed $feed, array $parameters)
    {
        return $this->processForm($feed, $parameters, 'PUT');
    }

    /**
     * Partially update a Feed.
     *
     * @param IFeed $feed
     * @param array         $parameters
     *
     * @return IFeed
     */
    public function patch(IFeed $feed, array $parameters)
    {
        return $this->processForm($feed, $parameters, 'PATCH');
    }

    /**
     * Processes the form.
     *
     * @param IFeed $feed
     * @param array         $parameters
     * @param String        $method
     *
     * @return IFeed
     *
     * @throws \ArthurHoaro\FeedsApiBundle\Exception\InvalidFormException
     */
    private function processForm(IFeed $feed, array $parameters, $method = "PUT")
    {
        $form = $this->formFactory->create(new FeedType(), $feed, array('method' => $method));
        $form->submit($parameters, 'PATCH' !== $method);
        if ($form->isValid()) {

            $feed = $form->getData();
            $this->om->persist($feed);
            $this->om->flush($feed);

            return $feed;
        }

        throw new InvalidFormException('Invalid submitted data', $form);
    }

    private function createFeed()
    {
        return new $this->entityClass();
    }
} 
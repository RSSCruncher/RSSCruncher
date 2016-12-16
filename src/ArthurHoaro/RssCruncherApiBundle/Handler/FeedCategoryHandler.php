<?php


namespace ArthurHoaro\RssCruncherApiBundle\Handler;


use ArthurHoaro\RssCruncherApiBundle\ApiEntity\CategoryDTO;
use ArthurHoaro\RssCruncherApiBundle\Entity\FeedCategory;
use ArthurHoaro\RssCruncherApiBundle\Entity\FeedGroup;
use ArthurHoaro\RssCruncherApiBundle\Exception\EntityExistsException;

class FeedCategoryHandler extends GenericHandler
{
    /**
     * @var FeedGroup
     */
    protected $feedGroup;

    /**
     * Retrieve an enabled UserFeed by its ID as an array.
     *
     * @param int   $id     Feed ID.
     * @param array $params Additional parameters.
     *
     * @return FeedCategory List containing the categories found or null.
     */
    public function select($id, $params = []) {
        $keys = ['id', 'name'];
        $key = ctype_digit($id) ? 0 : 1;

        $res = $this->repository->findOneBy(array_merge(
            [
                $keys[$key] => $id,
            ],
            $params
        ));
        if (! empty($res)) {
            return $res;
        } else if ($key === 0) {
            // Digit? let's try by name
            return $res = $this->repository->findOneBy(array_merge(
                [
                    $keys[($key + 1) % 2] => $id,
                ],
                $params
            ));
        }
    }

    public function post(array $parameters) {
        if (empty($this->feedGroup)) {
            throw new \Exception('FeedGroup must be set to create a new FeedCategory.');
        }

        /** @var FeedCategory $entity */
        $entity = $this->processForm($this->create(), $parameters, 'POST');

        $category = $this->repository->findOneBy([
            'name' => $entity->getName(),
            'feedGroup' => $this->feedGroup->getId(),
        ]);
        if (! empty($category)) {
            throw new EntityExistsException($category, CategoryDTO::class);
        }

        $entity->setFeedGroup($this->feedGroup);
        $this->om->persist($entity);
        $this->om->flush();

        return $entity;
    }

    /**
     * Set the FeedGroup.
     *
     * @param FeedGroup $feedGroup
     *
     * @return FeedCategoryHandler
     */
    public function setFeedGroup($feedGroup)
    {
        $this->feedGroup = $feedGroup;

        return $this;
    }
}
<?php


namespace ArthurHoaro\RssCruncherApiBundle\Controller;

use ArthurHoaro\RssCruncherApiBundle\ApiEntity\ArticleDTO;
use ArthurHoaro\RssCruncherApiBundle\ApiEntity\CategoryDTO;
use ArthurHoaro\RssCruncherApiBundle\ApiEntity\UserFeedDTO;
use ArthurHoaro\RssCruncherApiBundle\Entity\FeedCategory;
use ArthurHoaro\RssCruncherApiBundle\Entity\FeedCategoryRepository;
use ArthurHoaro\RssCruncherApiBundle\Entity\ProxyUser;
use ArthurHoaro\RssCruncherApiBundle\Exception\EntityExistsException;
use ArthurHoaro\RssCruncherApiBundle\Exception\InvalidFormException;
use ArthurHoaro\RssCruncherApiBundle\Handler\FeedCategoryHandler;
use FOS\RestBundle\Request\ParamFetcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class FeedCategoryController extends ApiController
{
    /**
     * get categories
     *
     * @Annotations\QueryParam(name="offset", requirements="\d+", nullable=true, description="Offset from which to start listing feeds.")
     * @Annotations\QueryParam(name="limit", requirements="\d+", default="5", description="How many feeds to return.")
     *
     * @param Request $request
     * @param ParamFetcherInterface $paramFetcher
     *
     * @return CategoryDTO[]
     */
    public function getCategoriesAction(Request $request, ParamFetcherInterface $paramFetcher)
    {
        $offset = null == $paramFetcher->get('offset') ? 0 : (int) $paramFetcher->get('offset');
        $limit = null == $paramFetcher->get('limit') ? 0 : (int) $paramFetcher->get('limit');

        $em = $this->getDoctrine()->getManager();
        /** @var FeedCategoryRepository $feedRepository */
        $categoryRepo = $em->getRepository(FeedCategory::class);
        $categories = $categoryRepo->findByFeedGroup(
            $this->getProxyUser()->getFeedGroup(),
            ['name' => 'ASC'],
            $limit,
            $offset
        );
        $apiCategories = [];
        foreach ($categories as $category) {
            $apiCategories[] = (new CategoryDTO())->setEntity($category);
        }

        return $apiCategories;
    }

    /**
     * @param $id
     *
     * @return CategoryDTO
     */
    public function getCategoryAction($id)
    {
        $cat = $this->getOr404($id, $this->getProxyUser());
        return (new CategoryDTO())->setEntity($cat);
    }

    public function postCategoryAction(Request $request)
    {
        try {
            /** @var FeedCategoryHandler $handler */
            $handler = $this->get('arthur_hoaro_rss_cruncher_api.category.handler');
            $handler->setFeedGroup($this->getProxyUser()->getFeedGroup());

            $response = new Response();

            try {
                $entity = $handler->post($request->request->all());
                $response->setStatusCode(Response::HTTP_CREATED);
                $content = (new CategoryDTO())->setEntity($entity);
            } catch (EntityExistsException $e) {
                $response->setStatusCode(Response::HTTP_CONFLICT);
                $content = $e->getApiEntity();
            }

            $serializer = $this->get('jms_serializer');
            $content = $serializer->serialize($content, 'json');
            $response->setContent($content);

            return $response;
        } catch (InvalidFormException $exception) {
            return $exception->getForm();
        }
    }

    public function putCategoryAction(Request $request, $id)
    {
        $cat = $this->getOr404($id, $this->getProxyUser());
        $parameters = $request->request->all();
        /** @var FeedCategoryHandler $handler */
        $handler = $this->get('arthur_hoaro_rss_cruncher_api.category.handler');
        try {
            $entity = $handler->put($cat, $parameters);
        } catch (InvalidFormException $e) {
            return $e->getForm();
        }
        return (new CategoryDTO())->setEntity($entity);
    }

    public function patchCategoryAction(Request $request, $id)
    {
        $cat = $this->getOr404($id, $this->getProxyUser());
        $parameters = $request->request->all();
        /** @var FeedCategoryHandler $handler */
        $handler = $this->get('arthur_hoaro_rss_cruncher_api.category.handler');
        try {
            $entity = $handler->patch($cat, $parameters);
        } catch (InvalidFormException $e) {
            return $e->getForm();
        }
        return (new CategoryDTO())->setEntity($entity);
    }

    public function deleteCategoryAction($id)
    {
        $cat = $this->getOr404($id, $this->getProxyUser());
        $this->getDoctrine()->getManager()->remove($cat);
        $this->getDoctrine()->getManager()->flush();
    }

    public function getCategoryFeedsAction($id)
    {
        $cat = $this->getOr404($id, $this->getProxyUser());
        $feeds = [];
        foreach ($cat->getUserFeeds() as $feed) {
            $feeds[] = (new UserFeedDTO())->setEntity($feed);
        }
        return $feeds;
    }

    public function getCategoryArticlesAction($id)
    {
        $cat = $this->getOr404($id, $this->getProxyUser());
        $repo = $this->getDoctrine()->getRepository(FeedCategory::class);
        $userFeeds = $repo->findUserFeedArticles($cat);
        $out = [];
        foreach ($userFeeds as $userFeed) {
            foreach ($userFeed->getFeed()->getArticles() as $article) {
                $out[] = (new ArticleDTO())->setEntity($article, $userFeed);
            }
        }
        // Sort by date asc
        usort($out, function($a, $b) {
            return $a->getPublicationDate() < $b->getPublicationDate() ? 1 : -1;
        });
        return $out;
    }

    /**
     * Fetch a IEntity or throw an 404 Exception.
     *
     * @param int       $id
     * @param ProxyUser $proxyUser
     *
     * @return FeedCategory
     *
     * @throws NotFoundHttpException
     */
    protected function getOr404($id, ProxyUser $proxyUser)
    {
        $handler = $this->get('arthur_hoaro_rss_cruncher_api.category.handler');
        $cat = $handler->select($id, ['feedGroup' => $proxyUser->getFeedGroup()]);
        if (empty($cat)) {
            throw new NotFoundHttpException(sprintf('The resource \'%s\' was not found.', $id));
        }
        return $cat;
    }
}

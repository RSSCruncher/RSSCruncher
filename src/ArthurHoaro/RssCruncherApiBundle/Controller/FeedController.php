<?php

namespace ArthurHoaro\RssCruncherApiBundle\Controller;

use ArthurHoaro\RssCruncherApiBundle\ApiEntity\ArticleDTO;
use ArthurHoaro\RssCruncherApiBundle\ApiEntity\UserFeedDTO;
use ArthurHoaro\RssCruncherApiBundle\Entity\Article;
use ArthurHoaro\RssCruncherApiBundle\Entity\Feed;
use ArthurHoaro\RssCruncherApiBundle\Entity\FeedRepository;
use ArthurHoaro\RssCruncherApiBundle\Entity\ProxyUser;
use ArthurHoaro\RssCruncherApiBundle\Entity\User;
use ArthurHoaro\RssCruncherApiBundle\Entity\UserFeed;
use ArthurHoaro\RssCruncherApiBundle\Entity\UserFeedRepository;
use ArthurHoaro\RssCruncherApiBundle\Exception\FeedExistsException;
use ArthurHoaro\RssCruncherApiBundle\Exception\FeedNotFoundException;
use ArthurHoaro\RssCruncherApiBundle\Exception\InvalidFormException;
use ArthurHoaro\RssCruncherApiBundle\Form\FeedType;
use ArthurHoaro\RssCruncherApiBundle\Form\UserFeedType;
use ArthurHoaro\RssCruncherApiBundle\Handler\FeedHandler;
use ArthurHoaro\RssCruncherApiBundle\Handler\UserFeedHandler;
use ArthurHoaro\RssCruncherClientBundle\Entity\Client;
use ArthurHoaro\RssCruncherClientBundle\Helper\ClientHelper;
use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use SimplePMS\Message;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\Forms;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class FeedController
 *
 * Controller for API call related to Feeds (and mostly UserFeeds).
 *
 * @package ArthurHoaro\RssCruncherApiBundle\Controller
 */
class FeedController extends ApiController {
    /**
     * List all feeds (UserFeeds) attached to a ProxyUser.
     *
     * FIXME! offset+limit
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   }
     * )
     *
     * @Annotations\QueryParam(name="offset", requirements="\d+", nullable=true, description="Offset from which to start listing feeds.")
     * @Annotations\QueryParam(name="limit", requirements="\d+", default="5", description="How many feeds to return.")
     *
     * @Annotations\View(
     *  templateVar="feeds"
     * )
     *
     * @param Request               $request      the request object
     * @param ParamFetcherInterface $paramFetcher param fetcher service
     *
     * @return UserFeed[] List of UserFeeds entities formatted for the API (UserFeedDTO).
     */
    public function getFeedsAction(Request $request, ParamFetcherInterface $paramFetcher)
    {
        $offset = null == $paramFetcher->get('offset') ? 0 : (int) $paramFetcher->get('offset');
        $limit = null == $paramFetcher->get('limit') ? 0 : (int) $paramFetcher->get('limit');

        $em = $this->getDoctrine()->getManager();
        /** @var UserFeedRepository $feedRepository */
        $userFeedRepository = $em->getRepository(UserFeed::class);
        $userFeeds = $userFeedRepository->findByProxyUser($this->getProxyUser(), $limit, $offset);
        $apiFeeds = [];
        foreach ($userFeeds as $feed) {
            $apiFeeds[] = (new UserFeedDTO())->setEntity($feed);
        }

        return $apiFeeds;
    }
    
    /**
     * Get single UserFeed.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Gets a IEntity for a given id",
     *   output = "ArthurHoaro\RssCruncherApiBundle\Entity\Feed",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the feed is not found"
     *   }
     * )
     *
     * @Annotations\View(templateVar="feed")
     *
     * @param int     $id      the feed id
     *
     * @return UserFeedDTO
     *
     * @throws NotFoundHttpException when feed not exist
     */
	public function getFeedAction($id) {
        $userFeed = $this->getOr404($id, $this->getProxyUser());
		return (new UserFeedDTO())->setEntity($userFeed);
	}

    /**
     * Get articles attached to a feed.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Gets a IEntity for a given id",
     *   output = "ArthurHoaro\RssCruncherApiBundle\Entity\Article",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the feed is not found"
     *   }
     * )
     *
     * @Annotations\View(templateVar="feed")
     *
     * @param int     $id      the feed id
     *
     * @return ArticleDTO[]
     *
     * @throws NotFoundHttpException when feed not exist
     */
    public function getFeedArticlesAction($id) {
        $feed = $this->getOr404($id, $this->getProxyUser());

        $articles = [];
        foreach ($feed->getFeed()->getArticles() as $article) {
            $articles[] = (new ArticleDTO())->setEntity($article, $feed);
        }
        return $articles;
    }

    /**
     * Create a Feed from the submitted data.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Creates a new feed from the submitted data.",
     *   input = "ArthurHoaro\RssCruncherApiBundle\Form\FeedType",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     * @param Request $request the request object
     *
     * @return Response
     */
    public function postFeedAction(Request $request)
    {
        try {
            /** @var UserFeedHandler $handler */
            $handler = $this->get('arthur_hoaro_rss_cruncher_api.user_feed.handler');
            $handler->setProxyUser($this->getProxyUser());

            $response = new Response();

            try {
                $entity = $handler->post($request->request->all());
                $response->setStatusCode(Response::HTTP_CREATED);
                $content = (new UserFeedDTO())->setEntity($entity);

                $pms = $this->get('arthur_hoaro_rss_cruncher_api.queue_manager')->getManager();
                $pms->send($entity, 'update');
            } catch (FeedExistsException $e) {
                $response->setStatusCode(Response::HTTP_CONFLICT);
                $content = $e->getFeed();
            }

            $serializer = $this->get('jms_serializer');
            $content = $serializer->serialize($content, 'json');
            $response->setContent($content);
            return $response;
        } catch (InvalidFormException $exception) {
            return $exception->getForm();
        }
    }

    /**
     * Update existing feed from the submitted data or create a new feed at a specific location.
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "ArthurHoaro\RssCruncherApiBundle\Form\FeedType",
     *   statusCodes = {
     *     201 = "Returned when the Feed is created",
     *     204 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     * @Annotations\View(
     *  template = "AcmeBlogBundle:Feed:editFeed.html.twig",
     *  templateVar = "form"
     * )
     *
     * @param Request $request the request object
     * @param int     $id      the feed id
     *
     * @return UserFeedDTO
     *
     * @throws NotFoundHttpException when feed not exist
     */
    public function putFeedAction(Request $request, $id)
    {
        $feed = $this->getOr404($id, $this->getProxyUser());
        $parameters = $request->request->all();
        /** @var UserFeedHandler $handler */
        $handler = $this->get('arthur_hoaro_rss_cruncher_api.user_feed.handler');
        $entity = $handler->put($feed, $parameters);
        return (new UserFeedDTO())->setEntity($entity);
    }

    /**
     * Update existing feed from the submitted data or create a new feed at a specific location.
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "ArthurHoaro\RssCruncherApiBundle\Form\FeedType",
     *   statusCodes = {
     *     204 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     * @Annotations\View(
     *  template = "AcmeBlogBundle:Feed:editFeed.html.twig",
     *  templateVar = "form"
     * )
     *
     * @param Request $request the request object
     * @param int     $id      the feed id
     *
     * @return UserFeedDTO
     *
     * @throws NotFoundHttpException when feed not exist
     */
    public function patchFeedAction(Request $request, $id)
    {
        /** @var UserFeed $feed */
        $feed = $this->get('arthur_hoaro_rss_cruncher_api.user_feed.handler')->patch(
            $this->getOr404($id, $this->getProxyUser()),
            $request->request->all()
        );

        return (new UserFeedDTO())->setEntity($feed);
    }

    /**
     * Fetch a IEntity or throw an 404 Exception.
     *
     * @param int       $id
     * @param ProxyUser $proxyUser
     *
     * @return UserFeed
     *
     * @throws NotFoundHttpException
     */
    protected function getOr404($id, ProxyUser $proxyUser)
    {
        $handler = $this->get('arthur_hoaro_rss_cruncher_api.user_feed.handler');
        $feed = $handler->get($id, ['feedGroup' => $proxyUser->getFeedGroup()]);
        if (empty($feed)) {
            throw new NotFoundHttpException(sprintf('The resource \'%s\' was not found.', $id));
        }
        return $feed;
    }

    /**
     * Refresh Articles of a single Feed
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Refresh a feed to find its new Articles, return all new or updated Articles",
     *   output = "ArthurHoaro\RssCruncherApiBundle\Entity\Article",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the feed is not found"
     *   }
     * )
     *
     * @Annotations\View(templateVar="feed")
     *
     * @param int     $id      the feed id
     *
     * @return ArticleDTO[]
     */
    public function getFeedRefreshAction($id)
    {
        $userFeedHandler = $this->get('arthur_hoaro_rss_cruncher_api.user_feed.handler');
        $feedHandler = $this->get('arthur_hoaro_rss_cruncher_api.feed.handler');
        /** @var UserFeed $userFeed */
        $userFeed = $userFeedHandler->get($id, ['feedGroup' => $this->getProxyUser()->getFeedGroup()]);
        if (empty($userFeed)) {
            throw new FeedNotFoundException($id);
        }

        $items = $feedHandler->refreshFeed(
            $userFeed->getFeed(),
            $this->get('simplepie')
        );

        $validator = $this->get('validator');

        $articles = [];
        foreach ($items as $item) {
            if (count($validator->validate($item)) == 0) {
                $articles[] = $this->get('arthur_hoaro_rss_cruncher_api.article.handler')->save($item);
            }
        }

        $feedHandler->updateDateFetch($userFeed->getFeed());

        $out = [];
        foreach ($articles as $v) {
            $out[] = (new ArticleDTO())->setEntity($v, $userFeed);
        }
        return $out;
    }

    /**
     * @param int $id
     */
    public function deleteFeedAction($id)
    {
        /** @var UserFeedHandler $userFeedHandler */
        $userFeedHandler = $this->get('arthur_hoaro_rss_cruncher_api.user_feed.handler');
        $userFeed = $userFeedHandler->get($id, ['feedGroup' => $this->getProxyUser()->getFeedGroup()]);
        if (empty($userFeed)) {
            throw new FeedNotFoundException($id);
        }
        $userFeedHandler->disable($userFeed);
    }
}

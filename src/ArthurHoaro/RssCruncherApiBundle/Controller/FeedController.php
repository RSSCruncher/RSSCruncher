<?php

namespace ArthurHoaro\RssCruncherApiBundle\Controller;

use ArthurHoaro\RssCruncherApiBundle\Exception\InvalidFormException;
use ArthurHoaro\RssCruncherApiBundle\Form\FeedType;
use ArthurHoaro\RssCruncherApiBundle\Handler\FeedHandler;
use ArthurHoaro\RssCruncherApiBundle\Model\IFeed;
use ArthurHoaro\RssCruncherClientBundle\Entity\Client;
use ArthurHoaro\RssCruncherClientBundle\Helper\ClientHelper;
use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class FeedController extends ApiController {
    /**
     * List all feeds.
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
     * @return array
     */
    public function getFeedsAction(Request $request, ParamFetcherInterface $paramFetcher)
    {
        $offset = $paramFetcher->get('offset');
        $offset = null == $offset ? 0 : $offset;
        $limit = $paramFetcher->get('limit');

        /** @var FeedHandler $feedHandler */
        $feedHandler = $this->container->get('arthur_hoaro_rss_cruncher_api.feed.handler');
        $feedHandler->allUser($this->getProxyUser(), $limit, $offset);
    }
    
    /**
     * Get single Feed,
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
     * @return array
     *
     * @throws NotFoundHttpException when feed not exist
     */
	public function getFeedAction($id) {
		return $this->getOr404($id);
	}

    /**
     * Get a feed's articles
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
     * @return array
     *
     * @throws NotFoundHttpException when feed not exist
     */
    public function getFeedArticlesAction($id) {
        $feed = $this->getOr404($id);
        return $feed->getArticles();
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
     * @Annotations\View(
     *  template = "AcmeBlogBundle:Feed:newFeed.html.twig",
     *  statusCode = Response::HTTP_BAD_REQUEST,
     *  templateVar = "form"
     * )
     *
     * @param Request $request the request object
     *
     * @return FormTypeInterface|View
     */
    public function postFeedAction(Request $request)
    {
        try {
            // Hey Feed handler create a new Feed.
            $newFeed = $this->container->get('arthur_hoaro_rss_cruncher_api.feed.handler')->post(
                $request->request->all()
            );

            $routeOptions = array(
                'id' => $newFeed->getId(),
                '_format' => $request->get('_format')
            );

            return $this->routeRedirectView('api_1_get_feed', $routeOptions, Response::HTTP_CREATED);
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
     * @return FormTypeInterface|View
     *
     * @throws NotFoundHttpException when feed not exist
     */
    public function putFeedAction(Request $request, $id)
    {
        try {
            if (!($feed = $this->container->get('arthur_hoaro_rss_cruncher_api.feed.handler')->get($id))) {
                $statusCode = Response::HTTP_CREATED;
                $feed = $this->container->get('arthur_hoaro_rss_cruncher_api.feed.handler')->post(
                    $request->request->all()
                );
            } else {
                $statusCode = Response::HTTP_NO_CONTENT;
                $feed = $this->container->get('arthur_hoaro_rss_cruncher_api.feed.handler')->put(
                    $feed,
                    $request->request->all()
                );
            }

            $routeOptions = array(
                'id' => $feed->getId(),
                '_format' => $request->get('_format')
            );

            return $this->routeRedirectView('api_1_get_feed', $routeOptions, $statusCode);

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
     * @return FormTypeInterface|View
     *
     * @throws NotFoundHttpException when feed not exist
     */
    public function patchFeedAction(Request $request, $id)
    {
        try {
            $feed = $this->container->get('arthur_hoaro_rss_cruncher_api.feed.handler')->patch(
                $this->getOr404($id),
                $request->request->all()
            );

            $routeOptions = array(
                'id' => $feed->getId(),
                '_format' => $request->get('_format')
            );

            return $this->routeRedirectView('api_1_get_feed', $routeOptions, Response::HTTP_NO_CONTENT);

        } catch (InvalidFormException $exception) {

            return $exception->getForm();
        }
    }

    /**
     * Presents the form to use to create a new feed.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   }
     * )
     *
     * @Annotations\View()
     *
     * @return FormTypeInterface
     */
    public function newFeedAction()
    {
        return $this->createForm(new FeedType());
    }

    /**
     * Fetch a IEntity or throw an 404 Exception.
     *
     * @param mixed $id
     *
     * @return IFeed
     *
     * @throws NotFoundHttpException
     */
    protected function getOr404($id)
    {
        if (!($feed = $this->container->get('arthur_hoaro_rss_cruncher_api.feed.handler')->get($id))) {
            throw new NotFoundHttpException(sprintf('The resource \'%s\' was not found.',$id));
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
     * @return array
     *
     */
    public function refreshFeedAction($id) {
        $items = $this->container->get('arthur_hoaro_rss_cruncher_api.feed.handler')->refreshFeed(
            $id,
            $this->container->get('debril.reader')
        );

        $validator = $this->get('validator');

        foreach($items as $item) {
            if( count($validator->validate($item)) == 0 ) {
                $articles[] = $this->container->get('arthur_hoaro_rss_cruncher_api.article.handler')->save($item);
            }
        }

        return $items;
    }
}
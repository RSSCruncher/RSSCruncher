<?php

namespace ArthurHoaro\RssCruncherApiBundle\Controller;

use ArthurHoaro\RssCruncherApiBundle\ApiEntity\FeedDTO;
use ArthurHoaro\RssCruncherApiBundle\Entity\Feed;
use ArthurHoaro\RssCruncherApiBundle\Entity\FeedRepository;
use ArthurHoaro\RssCruncherApiBundle\Entity\UserFeed;
use ArthurHoaro\RssCruncherApiBundle\Entity\UserFeedRepository;
use ArthurHoaro\RssCruncherApiBundle\Exception\InvalidFormException;
use ArthurHoaro\RssCruncherApiBundle\Form\FeedType;
use ArthurHoaro\RssCruncherApiBundle\Form\UserFeedType;
use ArthurHoaro\RssCruncherApiBundle\Handler\FeedHandler;
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

        $em = $this->getDoctrine()->getManager();
        /** @var UserFeedRepository $feedRepository */
        $userFeedRepository = $em->getRepository(UserFeed::class);
        $userFeeds = $userFeedRepository->findByProxyUser($this->getProxyUser());
        $apiFeeds = [];
        foreach ($userFeeds as $feed) {
            $apiFeeds[] = (new FeedDTO())->setEntity($feed);
        }

        return $apiFeeds;
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
     * @param Request $request the request object
     *
     * @return FormTypeInterface|View
     */
    public function postFeedsAction(Request $request)
    {
        try {
            $handler = $this->get('arthur_hoaro_rss_cruncher_api.user_feed.handler');
            /** @var UserFeed $entity */
            $entity = $handler->post($request->request->all());
            $entity->setProxyUser($this->getProxyUser());

            // Save
            $this->getDoctrine()->getManager()->persist($entity);
            $this->getDoctrine()->getManager()->flush();

            $response = new Response();
            $response->setStatusCode(Response::HTTP_CREATED);

            $pms = $this->container->get('arthur_hoaro_rss_cruncher_api.queue_manager')->getManager();
            $pms->send($entity, 'update');

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
     * @return FormTypeInterface|View
     *
     * @throws NotFoundHttpException when feed not exist
     */
    public function putFeedAction(Request $request, $id)
    {
        try {
            $parameters = $request->request->all();

            if (!($feed = $this->container->get('arthur_hoaro_rss_cruncher_api.feed.handler')->get($id))) {
                $statusCode = Response::HTTP_CREATED;
            } else {
                $statusCode = Response::HTTP_NO_CONTENT;
            }

            // Validate input.
            /** @var FormInterface $form */
            $form = $this->get('form.factory')->create(
                UserFeedType::class,
                new UserFeed(),
                ['method' => 'PUT']
            );
            $form->submit($parameters);
            if (!$form->isValid()) {
                throw new InvalidFormException('Invalid submitted data', $form);
            }
            /** @var UserFeed $entity */
            $entity = $form->getData();

            $em = $this->getDoctrine()->getManager();
            /** @var FeedRepository $feedRepository */
            $feedRepository = $em->getRepository(Feed::class);
            // Retrieve or create the existing Feed matching our feedurl.
            $feed = $feedRepository->findByUrlOrCreate($parameters['feedurl']);

            // Attach stuff
            $entity->setFeed($feed);
            $entity->setProxyUser($this->getProxyUser());

            // Save
            $em->persist($entity);
            $em->flush();

            $response = new Response();
            $response->setStatusCode($statusCode);

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
     * @return Feed
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
    public function getFeedRefreshAction($id) {
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
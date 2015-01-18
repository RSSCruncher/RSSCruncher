<?php

namespace ArthurHoaro\FeedsApiBundle\Controller;

use ArthurHoaro\FeedsApiBundle\Exception\InvalidFormException;
use ArthurHoaro\FeedsApiBundle\Form\ArticleType;
use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ArticleController extends FOSRestController {
    /**
     * List all articles.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   }
     * )
     *
     * @Annotations\QueryParam(name="offset", requirements="\d+", nullable=true, description="Offset from which to start listing feeds.")
     * @Annotations\QueryParam(name="limit", requirements="\d+", default="20", description="How many feeds to return.")
     *
     * @Annotations\View(
     *  templateVar="articles"
     * )
     *
     * @param Request               $request      the request object
     * @param ParamFetcherInterface $paramFetcher param fetcher service
     *
     * @return array
     */
    public function getArticlesAction(Request $request, ParamFetcherInterface $paramFetcher)
    {
        $offset = $paramFetcher->get('offset');
        $offset = null == $offset ? 0 : $offset;
        $limit = $paramFetcher->get('limit');

        return $this->container->get('arthur_hoaro_feeds_api.article.handler')->all($limit, $offset);
    }
    
    /**
     * Get single Article,
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Gets a IEntity for a given id",
     *   output = "ArthurHoaro\FeedsApiBundle\Entity\Feed",
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
	public function getArticleAction($id) {
		return $this->getOr404($id);
	}

    /**
     * Fetch a IEntity or throw an 404 Exception.
     *
     * @param mixed $id
     *
     * @return \ArthurHoaro\FeedsApiBundle\Model\IEntity
     *
     * @throws NotFoundHttpException
     */
    protected function getOr404($id)
    {
        if (!($feed = $this->container->get('arthur_hoaro_feeds_api.article.handler')->get($id))) {
            throw new NotFoundHttpException(sprintf('The resource \'%s\' was not found.',$id));
        }
        return $feed;
    }

    /**
     * Create a Article from the submitted data.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Creates a new feed from the submitted data.",
     *   input = "ArthurHoaro\FeedsApiBundle\Form\ArticleType",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     * @Annotations\View(
     *  template = "AcmeBlogBundle:Article:newArticle.html.twig",
     *  statusCode = Codes::HTTP_BAD_REQUEST,
     *  templateVar = "form"
     * )
     *
     * @param Request $request the request object
     *
     * @return FormTypeInterface|View
     */
    public function postArticleAction(Request $request)
    {
        try {
            // Hey Feed handler create a new Feed.
            $newFeed = $this->container->get('arthur_hoaro_feeds_api.article.handler')->post(
                $request->request->all()
            );

            $routeOptions = array(
                'id' => $newFeed->getId(),
                '_format' => $request->get('_format')
            );

            return $this->routeRedirectView('api_1_get_article', $routeOptions, Codes::HTTP_CREATED);
        } catch (InvalidFormException $exception) {

            return $exception->getForm();
        }
    }

    /**
     * Update existing Article from the submitted data or create a new article at a specific location.
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "ArthurHoaro\FeedsApiBundle\Form\ArticleType",
     *   statusCodes = {
     *     201 = "Returned when the Article is created",
     *     204 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     * @Annotations\View(
     *  template = "AcmeBlogBundle:Article:editArticle.html.twig",
     *  templateVar = "form"
     * )
     *
     * @param Request $request the request object
     * @param int     $id      the article id
     *
     * @return FormTypeInterface|View
     *
     * @throws NotFoundHttpException when feed not exist
     */
    public function putArticleAction(Request $request, $id)
    {
        try {
            if (!($article = $this->container->get('arthur_hoaro_feeds_api.article.handler')->get($id))) {
                $statusCode = Codes::HTTP_CREATED;
                $article = $this->container->get('arthur_hoaro_feeds_api.article.handler')->post(
                    $request->request->all()
                );
            } else {
                $statusCode = Codes::HTTP_NO_CONTENT;
                $article = $this->container->get('arthur_hoaro_feeds_api.article.handler')->put(
                    $article,
                    $request->request->all()
                );
            }

            $routeOptions = array(
                'id' => $article->getId(),
                '_format' => $request->get('_format')
            );

            return $this->routeRedirectView('api_1_get_article', $routeOptions, $statusCode);

        } catch (InvalidFormException $exception) {

            return $exception->getForm();
        }
    }

    /**
     * Update existing article from the submitted data or create a new article at a specific location.
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "ArthurHoaro\FeedsApiBundle\Form\ArticleType",
     *   statusCodes = {
     *     204 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     * @Annotations\View(
     *  template = "AcmeBlogBundle:Article:editArticle.html.twig",
     *  templateVar = "form"
     * )
     *
     * @param Request $request the request object
     * @param int     $id      the article id
     *
     * @return FormTypeInterface|View
     *
     * @throws NotFoundHttpException when article not exist
     */
    public function patchArticleAction(Request $request, $id)
    {
        try {
            $article = $this->container->get('arthur_hoaro_feeds_api.article.handler')->patch(
                $this->getOr404($id),
                $request->request->all()
            );

            $routeOptions = array(
                'id' => $article->getId(),
                '_format' => $request->get('_format')
            );

            return $this->routeRedirectView('api_1_get_article', $routeOptions, Codes::HTTP_NO_CONTENT);

        } catch (InvalidFormException $exception) {

            return $exception->getForm();
        }
    }

    /**
     * Presents the form to use to create a new article.
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
    public function newArticleAction()
    {
        return $this->createForm(new ArticleType());
    }
} 
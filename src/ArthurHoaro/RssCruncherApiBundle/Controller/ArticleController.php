<?php

namespace ArthurHoaro\RssCruncherApiBundle\Controller;

use ArthurHoaro\RssCruncherApiBundle\ApiEntity\ArticleContentDTO;
use ArthurHoaro\RssCruncherApiBundle\ApiEntity\ArticleDTO;
use ArthurHoaro\RssCruncherApiBundle\ApiEntity\ArticleHistoryDTO;
use ArthurHoaro\RssCruncherApiBundle\Entity\Article;
use ArthurHoaro\RssCruncherApiBundle\Entity\ArticleContent;
use ArthurHoaro\RssCruncherApiBundle\Entity\ProxyUser;
use ArthurHoaro\RssCruncherApiBundle\Exception\InvalidFormException;
use ArthurHoaro\RssCruncherApiBundle\Form\ArticleType;
use ArthurHoaro\RssCruncherApiBundle\Handler\ArticleHandler;
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

/**
 * Class ArticleController
 *
 * API calls for Articles.
 *
 * @package ArthurHoaro\RssCruncherApiBundle\Controller
 */
class ArticleController extends ApiController {
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
        $offset = null == $paramFetcher->get('offset') ? 0 : (int) $paramFetcher->get('offset');
        $limit = null == $paramFetcher->get('limit') ? 0 : (int) $paramFetcher->get('limit');

        $repo = $this->getDoctrine()->getRepository(Article::class);
        $userFeeds = $repo->findUserFeedArticles($this->getProxyUser()->getFeedGroup(), $offset, $limit);
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
     * Get single Article,
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
     * @return ArticleDTO
     *
     * @throws NotFoundHttpException when feed not exist
     */
	public function getArticleAction($id)
    {
	    /** @var Article $article */
		$article = $this->getOr404($id, $this->getProxyUser());
		return (new ArticleDTO())->setEntity($article, $article->getFeed()->getUserFeeds()[0]);
	}

    /**
     * @Annotations\Route("/articles/{id}/history", requirements={"id" = "\d+"})
     * @Annotations\QueryParam(name="offset", requirements="\d+", description="Offset from which to start listing feeds.")
     * @Annotations\QueryParam(name="limit", requirements="\d+", default="5", description="How many feeds to return.")
     *
     * @param ParamFetcherInterface $paramFetcher
     * @return ArticleHistoryDTO
     */
	public function getArticleHistoryAction(ParamFetcherInterface $paramFetcher, $id)
    {
        $offset = null == $paramFetcher->get('offset') ? 0 : (int) $paramFetcher->get('offset');
        $limit = null == $paramFetcher->get('limit') ? 0 : (int) $paramFetcher->get('limit');

        /** @var Article $article */
        $article = $this->getOr404($id, $this->getProxyUser());
        $repo = $this->getDoctrine()->getRepository(ArticleContent::class);
        $history = $repo->findBy([
                'article' => $article
            ],
            [
                'date' => 'DESC'
            ],
            $limit,
            $offset
        );

        $dto = new ArticleHistoryDTO();
        $dto->setArticle((new ArticleDTO())->setEntity($article, $article->getFeed()->getUserFeeds()[0]));
        $contents = [];
        foreach ($history as $content)
        {
            $contents[] = (new ArticleContentDTO())->setEntity($content);
        }
        $dto->setHistory($contents);
        return $dto;
    }

    /**
     * Fetch a IEntity or throw an 404 Exception.
     *
     * @param int       $id
     * @param ProxyUser $proxyUser
     *
     * @return \ArthurHoaro\RssCruncherApiBundle\Model\IEntity
     *
     * @throws NotFoundHttpException
     */
    protected function getOr404($id, ProxyUser $proxyUser)
    {
        /** @var ArticleHandler $handler */
        $handler = $this->get('arthur_hoaro_rss_cruncher_api.article.handler');
        $handler->setFeedGroup($proxyUser->getFeedGroup());
        if (! ($article = $handler->get($id))) {
            throw new NotFoundHttpException(sprintf('The resource \'%s\' was not found.', $id));
        }
        return $article;
    }
}

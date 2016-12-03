<?php

namespace ArthurHoaro\RssCruncherApiBundle\Controller;


use ArthurHoaro\RssCruncherApiBundle\Entity\ProxyUser;
use ArthurHoaro\RssCruncherApiBundle\Handler\AccessTokenHandler;
use ArthurHoaro\RssCruncherApiBundle\Handler\ProxyUserHandler;
use FOS\RestBundle\Controller\FOSRestController;

/**
 * Class ApiController
 *
 * Main abstract class for all API controllers.
 * Used for common helper functions, such as retrieving a UserProxy.
 *
 * @package ArthurHoaro\RssCruncherApiBundle\Controller
 */
abstract class ApiController extends FOSRestController
{
    /**
     * @var ProxyUser $proxyUser
     */
    protected $proxyUser;

    /**
     * @var string
     */
    protected $token;

    /**
     * Get the current security token.
     *
     * @return string current token.
     */
    protected function getToken()
    {
        $token = $this->container->get('security.token_storage')->getToken();
        return $token->getToken();
    }

    /**
     * Retrieve the current ProxyUser (user+client) using the current access token.
     *
     * @return ProxyUser used to make this API call.
     *
     * @throws \Exception Invalid AccessToken.
     */
    protected function getProxyUser()
    {
        /** @var AccessTokenHandler $tokenHandler */
        $tokenHandler = $this->container->get('arthur_hoaro_rss_cruncher_api.access_token.handler');
        /** @var ProxyUserHandler $handler */
        $handler = $this->container->get('arthur_hoaro_rss_cruncher_api.proxy_user.handler');
        
        $token = $tokenHandler->getByToken($this->getToken());
        if (empty($token)) {
            throw new \Exception('AccesToken not found');
        }

        $proxyUser = $handler->getByToken($token);
        if (! empty($proxyUser)) {
            return $proxyUser;
        }

        $proxyUser = $handler->createUser($token->getClient(), $token->getUser());
        $em = $this->getDoctrine()->getManager();
        $em->persist($proxyUser);
        $em->flush();
        return $proxyUser;
    }
}

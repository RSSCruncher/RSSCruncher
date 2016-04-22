<?php
/**
 * ApiController.php
 * Author: arthur
 */

namespace ArthurHoaro\RssCruncherApiBundle\Controller;


use ArthurHoaro\RssCruncherApiBundle\Entity\ProxyUser;
use ArthurHoaro\RssCruncherApiBundle\Handler\AccessTokenHandler;
use ArthurHoaro\RssCruncherApiBundle\Handler\ProxyUserHandler;
use FOS\RestBundle\Controller\FOSRestController;

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
     * @return string
     */
    protected function getToken()
    {
        $tokenManager = $this->container->get('fos_oauth_server.access_token_manager.default');
        $token        = $this->container->get('security.token_storage')->getToken();
        return $token->getToken();
    }

    /**
     * @return ProxyUser
     *
     * @throws \Exception
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
        if (!empty($proxyUser)) {
            return $proxyUser;
        }

        $proxyUser = $handler->createUser($token->getClient(), $token->getUser());
        $em = $this->getDoctrine()->getManager();
        $em->persist($proxyUser);
        $em->flush();
        return $proxyUser;
    }
}
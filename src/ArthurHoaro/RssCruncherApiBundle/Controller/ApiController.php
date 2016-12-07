<?php

namespace ArthurHoaro\RssCruncherApiBundle\Controller;


use ArthurHoaro\RssCruncherApiBundle\Entity\ProxyUser;
use ArthurHoaro\RssCruncherApiBundle\Entity\ProxyUserRepository;
use ArthurHoaro\RssCruncherApiBundle\Exception\UserNotFoundException;
use ArthurHoaro\RssCruncherApiBundle\Handler\AccessTokenHandler;
use ArthurHoaro\RssCruncherApiBundle\Handler\ProxyUserHandler;
use FOS\RestBundle\Controller\FOSRestController;

/**
 * Class ApiController
 *
 * Main abstract class for all API controllers.
 * Used for common helper functions, such as retrieving a ProxyUser.
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
        return $this->get('security.token_storage')->getToken()->getToken();
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
        // FIXME! Test code in core is a bad practice
        // Firewalls are disabled in test env, so we retrieve the first ProxyUser
        if ($this->get('kernel')->getEnvironment() === 'test') {
            $handler = $this->get('arthur_hoaro_rss_cruncher_api.proxy_user.handler');
            return $handler->all(1)[0];
        }

        /** @var AccessTokenHandler $tokenHandler */
        $tokenHandler = $this->get('arthur_hoaro_rss_cruncher_api.access_token.handler');
        /** @var ProxyUserRepository $handler */
        $proxyUserRepo = $this->getDoctrine()->getManager()->getRepository(ProxyUser::class);
        
        $token = $tokenHandler->getByToken($this->getToken());
        if (empty($token)) {
            throw new \Exception('AccessToken not found');
        }

        // User isn't set if the app uses client_credentials grant access.
        if (empty($token->getUser())) {
            $repository = $this->getDoctrine()->getManager()->getRepository(ProxyUser::class);
            $proxyUser = $repository->findOneBy(['client' => $token->getClient()]);
            if (! empty($proxyUser)) {
                return $proxyUser;
            }
        }

        $proxyUser = $proxyUserRepo->findByUserClient($token->getUser(), $token->getClient());
        if (! empty($proxyUser)) {
            return $proxyUser;
        }

        throw new UserNotFoundException();
    }
}

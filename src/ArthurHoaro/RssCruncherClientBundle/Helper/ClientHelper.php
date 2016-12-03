<?php

namespace ArthurHoaro\RssCruncherClientBundle\Helper;

use ArthurHoaro\RssCruncherClientBundle\Entity\Client;
use Symfony\Component\DependencyInjection\Container;

/**
 * Class ClientHelper
 * @package ArthurHoaro\RssCruncherClientBundle\Helper
 */
class ClientHelper
{
    public static $GRANT_TYPE_CLIENT = 'client_credentials';

    public static $GRANT_TYPE_USER = 'authorization_code';

    /**
     * @param Container $container
     */
    public static function getCurrentClient($container)
    {
        $tokenManager = $container->get('fos_oauth_server.access_token_manager.default');
        $token        = $container->get('security.token_storage')->getToken();
        $accessToken  = $tokenManager->findTokenByToken($token->getToken());

        return $accessToken->getClient();
    }

    /**
     * @param Client $client
     * @return bool
     */
    public static function isGrantClient($client)
    {
        return $client->getAllowedGrantType() == self::$GRANT_TYPE_CLIENT;
    }

    /**
     * @param Client $client
     * @return bool
     */
    public static function isGrantUser($client)
    {
        return $client->getAllowedGrantType() == self::$GRANT_TYPE_USER;
    }
}
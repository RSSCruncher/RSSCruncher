<?php
/**
 * ProxyUserHandler.php
 * Author: arthur
 */

namespace ArthurHoaro\RssCruncherApiBundle\Handler;


use ArthurHoaro\RssCruncherApiBundle\Entity\AccessToken;
use ArthurHoaro\RssCruncherApiBundle\Entity\ProxyUser;
use ArthurHoaro\RssCruncherApiBundle\Entity\User;
use ArthurHoaro\RssCruncherClientBundle\Entity\Client;
use FOS\OAuthServerBundle\Model\ClientInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class ProxyUserHandler extends GenericHandler
{
    /**
     * Retrieve a ProxyUser by token.
     *
     * @param AccessToken $accessToken
     *
     * @return ProxyUser or null
     */
    public function getByToken($accessToken)
    {
        //return $this->repository->findByToken($accessToken);
        return $this->repository->findOneBy(['client' => $accessToken->getClient(), 'user' => $accessToken->getUser()]);
    }

    /**
     * Create a ProxyUser object.
     *
     * @param ClientInterface $client
     * @param UserInterface   $user
     *
     * @return ProxyUser
     */
    public function createUser($client, $user)
    {
        /** @var ProxyUser $entity */
        $entity = parent::create();
        $entity->setClient($client);
        $entity->setUser($user);
        return $entity;
    }
}

<?php

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
     * Retrieve a ProxyUser for a given user and client.
     *
     * @param ProxyUser $user
     * @param Client    $client
     *
     * @return ProxyUser or null
     */
    public function getByToken(ProxyUser $user, Client $client)
    {
        //return $this->repository->findByToken($accessToken);
        return $this->repository->findOneBy([
            'user' => $user,
            'client' => $client,
        ]);
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

        $this->om->persist($entity);
        $this->om->flush();

        return $entity;
    }
}

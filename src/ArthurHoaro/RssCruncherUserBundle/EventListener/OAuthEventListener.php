<?php


namespace ArthurHoaro\RssCruncherUserBundle\EventListener;


use ArthurHoaro\RssCruncherApiBundle\Entity\FeedGroup;
use ArthurHoaro\RssCruncherApiBundle\Entity\ProxyUser;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\NoResultException;
use FOS\OAuthServerBundle\Event\OAuthEvent;

class OAuthEventListener
{
    /**
     * @var ObjectManager
     */
    protected $om;

    public function __construct(ObjectManager $om)
    {
        $this->om = $om;
    }

    public function onPostAuthorizationProcess(OAuthEvent $event)
    {
        if ($event->isAuthorizedClient()) {
            $user = $event->getUser();
            $client = $event->getClient();

            $puRepo = $this->om->getRepository(ProxyUser::class);
            try {
                $puRepo->findByUserClient($user, $client);
                return;
            } catch (NoResultException $e) {
                $pu = new ProxyUser();
            }

            $pu->setClient($client);
            $pu->setUser($user);
            $this->om->persist($pu);

            $group = new FeedGroup();
            $group->addProxyUser($pu);
            $group->setName($client->getName());
            $this->om->persist($group);

            $pu->setMainFeedGroup($group);
            $this->om->persist($pu);

            $this->om->flush();
        }
    }
}

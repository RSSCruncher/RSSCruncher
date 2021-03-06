<?php

namespace ArthurHoaro\RssCruncherApiBundle\Entity;

use \FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use ArthurHoaro\RssCruncherApiBundle\Entity\ProxyUser;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var ProxyUser[]
     *
     * @ORM\OneToMany(targetEntity="ArthurHoaro\RssCruncherApiBundle\Entity\ProxyUser", mappedBy="user")
     */
    protected $proxyUsers;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @return ProxyUser[]
     */
    public function getProxyUsers()
    {
        return $this->proxyUsers;
    }

    /**
     * @param ProxyUser[] $proxyUsers
     */
    public function setProxyUsers($proxyUsers)
    {
        $this->proxyUsers = $proxyUsers;
    }
}

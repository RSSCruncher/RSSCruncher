<?php
/**
 * Created by PhpStorm.
 * User: ahoareau
 * Date: 06/02/2015
 * Time: 11:08
 */

namespace ArthurHoaro\RssCruncherApiBundle\Entity;

use FOS\OAuthServerBundle\Entity\RefreshToken as BaseRefreshToken;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class RefreshToken extends BaseRefreshToken
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Client")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $client;

    /**
     * @ORM\ManyToOne(targetEntity="ArthurHoaro\RssCruncherApiBundle\Entity\User")
     */
    protected $user;
}
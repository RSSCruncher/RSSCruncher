<?php
/**
 * Created by PhpStorm.
 * User: ahoareau
 * Date: 06/02/2015
 * Time: 11:25
 */

namespace ArthurHoaro\RssCruncherApiBundle\Entity;

use \FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

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

    public function __construct()
    {
        parent::__construct();
        // your own logic
    }
}
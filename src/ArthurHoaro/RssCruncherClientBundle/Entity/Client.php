<?php

namespace ArthurHoaro\RssCruncherClientBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\OAuthServerBundle\Entity\Client as BaseClient;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Client
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Client extends BaseClient
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    protected $name;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Client
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set first element of $redirectUris
     *
     * @param string $redirectUri
     */
    public function setRedirectUri($redirectUri)
    {
        parent::setRedirectUris([$redirectUri]);
    }

    /**
     * Set first element of $redirectUris
     *
     * @return string first redirectUri
     *
     * @Assert\Url()
     */
    public function getRedirectUri()
    {
        $uris = parent::getRedirectUris();
        return (count($uris)) ? $uris[0] : '';
    }
}


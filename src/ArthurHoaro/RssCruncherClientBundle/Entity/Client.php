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
    public static $GRANT_TYPE_CODE = 'code';
    public static $GRANT_TYPE_CLIENT = 'client';
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

    /**
     * @param string $grantType grant type.
     */
    public function setAllowedGrantType($grantType)
    {
        parent::setAllowedGrantTypes(['token', 'refresh_token'] + [$grantType]);
    }

    /**
     * @return array
     *
     * @Assert\EqualTo(
     *     [value => 'client_credentials', 'authorization_code']
     * )
     */
    public function getAllowedGrantType()
    {
        $types = array_diff(['token', 'refresh_token'], parent::getAllowedGrantTypes());
        return (count($types)) ? $types[0] : '';
    }


}


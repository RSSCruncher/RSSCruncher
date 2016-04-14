<?php

namespace ArthurHoaro\RssCruncherClientBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Testent
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Testent
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="abc", type="string", length=255)
     */
    private $abc;

    /**
     * @var integer
     *
     * @ORM\Column(name="test", type="integer")
     */
    private $test;


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
     * Set abc
     *
     * @param string $abc
     *
     * @return Testent
     */
    public function setAbc($abc)
    {
        $this->abc = $abc;

        return $this;
    }

    /**
     * Get abc
     *
     * @return string
     */
    public function getAbc()
    {
        return $this->abc;
    }

    /**
     * Set test
     *
     * @param integer $test
     *
     * @return Testent
     */
    public function setTest($test)
    {
        $this->test = $test;

        return $this;
    }

    /**
     * Get test
     *
     * @return integer
     */
    public function getTest()
    {
        return $this->test;
    }
}


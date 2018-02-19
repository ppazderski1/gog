<?php

namespace AppBundle\Entity;

use AppBundle\AppBundle;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;

/**
 * Product
 *
 * @ORM\Table(name="product")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProductRepository")
 * @UniqueEntity("name")
 */
class Product
{
    use ORMBehaviors\SoftDeletable\SoftDeletable;
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @Assert\NotBlank()
     * @Doctrine\ORM\Mapping\Column(type="string", nullable=false, length=255, unique=true)
     */
    private $name;

    /**
     * @Doctrine\ORM\Mapping\Column(type="integer", nullable=true, options={"unsigned"=true, "default":null})
     */
    private $stockSize;

    /**
     * @Doctrine\ORM\Mapping\OneToMany(targetEntity="Price", mappedBy="product")
     */
    private $prices;

    /**
     * @Doctrine\ORM\Mapping\Column(type="boolean", nullable=false, options={"default":"0"})
     */
    private $isProtected = false;


    public function __construct()
    {
        $this->prices = new ArrayCollection();
    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * @return int
     */
    public function getStockSize()
    {
        return $this->stockSize;
    }

    /**
     * @param mixed $stockSize
     */
    public function setStockSize(int $stockSize = null): void
    {
        $this->stockSize = $stockSize;
    }

    /**
     * @return Price[]
     */
    public function getPrices()
    {
        return $this->prices;
    }

    /**
     * @param mixed $prices
     */
    public function setPrices($prices): void
    {
        $this->prices = $prices;
    }

    /**
     * @return boolean
     */
    public function getIsProtected()
    {
        return $this->isProtected;
    }

    /**
     * @param mixed $isProtected
     */
    public function setIsProtected($isProtected): void
    {
        $this->isProtected = $isProtected;
    }

}

<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Price
 *
 * @ORM\Table(name="price")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PriceRepository")
 */
class Price
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @Doctrine\ORM\Mapping\Column(type="integer", nullable=false)
     */
    private $value;

    /**
     * @Doctrine\ORM\Mapping\Column(type="datetime", nullable=false)
     */
    private $validFrom;

    /**
     * @Doctrine\ORM\Mapping\Column(type="datetime", nullable=true)
     */
    private $validTo;

    /**
     * @Doctrine\ORM\Mapping\Column(type="boolean", nullable=false, options={"default":"0"})
     */
    private $isActive = false;

    /**
     * @Doctrine\ORM\Mapping\ManyToOne(targetEntity="Product", inversedBy="prices")
     * @Doctrine\ORM\Mapping\JoinColumn(name="product_id", referencedColumnName="id", nullable=false)
     */
    private $product;

    /**
     * @Doctrine\ORM\Mapping\ManyToOne(targetEntity="CurrencyZone")
     * @Doctrine\ORM\Mapping\JoinColumn(name="currency_zone_id", referencedColumnName="id", nullable=false)
     */
    private $currencyZone;

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
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     */
    public function setValue($value): void
    {
        $this->value = $value;
    }

    /**
     * @return mixed
     */
    public function getValidFrom()
    {
        return $this->validFrom;
    }

    /**
     * @param \DateTime $validFrom
     */
    public function setValidFrom( \DateTime $validFrom = null): void
    {
        if (null === $validFrom) {
            $validFrom = new \DateTime("now");
        }
        $this->validFrom = $validFrom;
    }

    /**
     * @return \DateTime
     */
    public function getValidTo()
    {
        return $this->validTo;
    }

    /**
     * @param \DateTime $validTo
     */
    public function setValidTo(\DateTime $validTo = null): void
    {
        $this->validTo = $validTo;
    }

    /**
     * @return boolean
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * @param boolean $isActive
     */
    public function setIsActive($isActive): void
    {
        $this->isActive = $isActive;
    }

    /**
     * @return \AppBundle\Entity\CurrencyZone
     */
    public function getCurrencyZone()
    {
        return $this->currencyZone;
    }

    /**
     * @param \AppBundle\Entity\CurrencyZone $currencyZone
     */
    public function setCurrencyZone($currencyZone): void
    {
        $this->currencyZone = $currencyZone;
    }

    /**
     * @param \AppBundle\Entity\Product $product
     */
    public function setProduct($product): void
    {
        $this->product = $product;
    }

}

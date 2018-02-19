<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping\UniqueConstraint;

/**
 * CurrencyZone
 *
 * @ORM\Table(name="currency_zone",
 *    uniqueConstraints={
 *        @UniqueConstraint(name="currency_locale_unique",
 *            columns={"currency", "locale"})
 *    }
 * )
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CurrencyZoneRepository")
 * @UniqueEntity("name")
 */
class CurrencyZone
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
     * @Assert\NotBlank()
     * @Doctrine\ORM\Mapping\Column(type="string", nullable=false, length=255, unique=true)
     */
    private $name;

    /**
     * @Assert\NotBlank()
     * @Assert\Currency()
     * @Doctrine\ORM\Mapping\Column(type="string", nullable=false, length=3)
     */
    protected $currency;

    /**
     * @Assert\NotBlank()
     * @Assert\Locale()
     * @Doctrine\ORM\Mapping\Column(type="string", nullable=false, length=10)
     */
    protected $locale;

    /**
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
     * @return string
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @param mixed $currency
     */
    public function setCurrency($currency): void
    {
        $this->currency = $currency;
    }

    /**
     * @return string
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * @param mixed $locale
     */
    public function setLocale($locale): void
    {
        $this->locale = $locale;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

}

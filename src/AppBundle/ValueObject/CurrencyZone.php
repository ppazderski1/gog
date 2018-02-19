<?php

namespace AppBundle\ValueObject;


/**
 * Class CurrencyZone
 * @package AppBundle\ValueObject
 */
class CurrencyZone extends Base
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $currency;

    /**
     * @var string
     */
    protected $locale;

    /**
     * CurrencyZone constructor.
     * @param int $id
     * @param string $name
     * @param string $currency
     * @param string $locale
     */
    public function __construct(int $id = null, string $name, string $currency, string $locale)
    {
        if( null !== $id && !is_int($id) ) {
            throw new \InvalidArgumentException('ID must be of type integer or null');
        }

        if( !is_string($name) && 0 === strlen(trim($name)) ) {
            throw new \InvalidArgumentException('Name must be nonempty string');
        }

        if ( strlen($currency) !== 3 || !ctype_alpha($currency) ) {
            throw new \InvalidArgumentException("Currency has to consist of three letters");
        }

        if ( !preg_match('/^[a-z]{2}_[A-Z]{2}$/', $locale) ) {
            throw new \InvalidArgumentException("Invalid locale code");
        }

        $this->id = $id;
        $this->name = $name;
        $this->currency = $currency;
        $this->locale = $locale;
    }

    /**
     * @param \AppBundle\Dto\CurrencyZone $currencyZoneDto
     * @return CurrencyZone
     */
    public static function createFromDto(\AppBundle\Dto\CurrencyZone $currencyZoneDto)
    {
        return new self(
            $currencyZoneDto->id,
            $currencyZoneDto->name,
            $currencyZoneDto->currency,
            $currencyZoneDto->locale
        );
    }
}
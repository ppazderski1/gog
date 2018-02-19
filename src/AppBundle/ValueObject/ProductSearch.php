<?php

namespace AppBundle\ValueObject;


/**
 * Class ProductSearch
 * @package AppBundle\ValueObject
 */
class ProductSearch extends BaseSearch
{
    /**
     * @var string
     */
    protected $name;

    protected $currencyZone;

    /**
     * ProductSearch constructor.
     * @param string $name
     * @param CurrencyZone $currencyZone
     * @param int $page
     * @param int $limit
     * @param array $sorts
     */
    public function __construct(string $name = null, CurrencyZone $currencyZone, int $page = 1, int $limit = 3, array $sorts = [])
    {
        $this->name = $name;
        $this->currencyZone = $currencyZone;

        parent::__construct($page, $limit, $sorts);
    }

}
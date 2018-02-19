<?php

namespace AppBundle\ValueObject;


/**
 * Class CurrencyZone
 * @package AppBundle\ValueObject
 */
class Collection extends Base
{
    /**
     * @var int
     */
    protected $total;


    /**
     * @var array
     */
    protected $collection;


    /**
     * CurrencyZone constructor.
     * @param int $total
     * @param int $page
     * @param array $collection
     */
    public function __construct(int $total,  array $collection)
    {
        $this->total = $total;
        $this->collection = $collection;
    }

}
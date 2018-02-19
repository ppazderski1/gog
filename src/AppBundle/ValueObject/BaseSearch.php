<?php

namespace AppBundle\ValueObject;


/**
 * Class BaseSearch
 * @package AppBundle\ValueObject
 */
abstract class BaseSearch extends Base
{
    /**
     * @var array
     */
    protected $sorts;

    /**
     * @var integer
     */
    protected $page;

    /**
     * @var integer
     */
    protected $limit;


    /**
     * BaseSearch constructor.
     * @param int $page
     * @param int $limit
     * @param array $sorts
     */
    public function __construct(int $page, int $limit, array $sorts = [])
    {
        if( !is_int($limit) || $limit < 1 ) {
            throw new \InvalidArgumentException('Limit must be integer greater then 0');
        }

        if( !is_int($page) || $page < 1 ) {
            throw new \InvalidArgumentException('Page must be integer greater then 0');
        }


        if( !is_array($sorts) ) {
            throw new \InvalidArgumentException('Sorts must be an array');
        }

        $sortArray = [];

        foreach ($sorts as $value) {
            $exploded = explode("_", $value);
            if(count($exploded) !== 2) {
                throw new \InvalidArgumentException('Invalid sort argument');
            }

            $property = $exploded[0];
            $way = $exploded[1];

            if( !is_string($property) && 0 === strlen(trim($property)) ) {
                throw new \InvalidArgumentException('Invalid sort key: ' . $property);
            }

            if( $way !== 'ASC' && $way !== 'DESC' ) {
                throw new \InvalidArgumentException('Value of sort must be either ASC or DESC');
            }

            $sortArray[$property] = $way;
        }

        $this->limit = $limit;
        $this->page = $page;
        $this->sorts = $sortArray;
    }

}
<?php

namespace micro\components\mongodb;


interface MongoArInterface
{
    /**
     * this function returns an array which maps some model attributes
     * to their strict type
     * available types are :
     * 1 - int
     * 2 - string
     * 3 - float
     * 4 - iso-date
     *
     * example :
     * return [
     *      'name' => 'string',
     *      'status' => 'int',
     *      'price' => 'float',
     *      'expire_at' => 'iso-date',
     *      ... => ...
     * ]
     *
     * @return array
     */
    function dataTypes();

}
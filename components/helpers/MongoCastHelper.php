<?php

namespace micro\components\helpers;

use yii\base\Component;
use MongoDB\BSON\ObjectID;
use MongoDB\BSON\UTCDateTime;
use yii\mongodb\ActiveRecord;

/**
 * Class MongoCastHelper
 * @package common\components
 */
class MongoCastHelper extends Component
{
    /**
     * @param $object
     * @return array|string
     */
    public static function magicCast($object) {
        if (is_array($object)) {
            foreach ($object as $key => $value) {
                if (is_array($value) || is_object($value)) {
                    $object[$key] = self::magicCast($value);
                }
            }
            return $object;

        } else {
            if( $object instanceof ObjectID){
                return (string) $object;
            }
            if( $object instanceof UTCDateTime){
                return (string) $object;
            }
            return [$object];
        }
    }

    /**
     * @param $value
     * @return bool
     */
    public static function isValidObjectID($value)
    {
        if ($value instanceof ObjectID) {
            return true;
        }
        try {
            new ObjectID($value);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * strict type casting for mongodb active records
     * TODO: we can do it as an interface later
     *
     * @param array $list
     * @param ActiveRecord $object
     */
    public static function strictCasting($list, &$object){

        foreach ($list as $key => $item){

            if( $object->isAttributeChanged($key) ) {

                \Yii::warning("changed attr : $key");

                if( isset($object->$key) && !empty($object->$key) ){
                    switch ($item) {
                        case 'int': {
                            $object->$key = (int) $object->$key;
                            break;
                        }
                        case 'float': {
                            $object->$key = (float) $object->$key;
                            break;
                        }
                        case 'string': {
                            $object->$key = (string) $object->$key;
                            break;
                        }
                        case 'iso-date': {
                            $object->$key = new UTCDateTime($object->$key);
                            break;
                        }
                    }
                }

            }

        }
    }
}
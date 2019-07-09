<?php

namespace micro\components\mongodb;

use micro\components\helpers\JalaliDateHelper;
use micro\components\helpers\MongoCastHelper;
use micro\components\helpers\PersianHelper;
use MongoDB\BSON\ObjectID;
use MongoDB\BSON\UTCDateTime;
use yii\data\ArrayDataProvider;
use yii\db\StaleObjectException;

class ActiveRecord extends \yii\mongodb\ActiveRecord implements MongoArInterface
{

    public function dataTypes()
    {
        return [];
    }

    protected function typeCast(){
        foreach ($this->dataTypes() as $key => $item){

            if( $this->isAttributeChanged($key) ) {

                \Yii::warning("changed attr : $key");

                if( isset($this->$key) && ( !empty($this->$key) || $this->$key == 0 ) ){
                    switch ($item) {
                        case 'int': {
                            $this->$key = (int) $this->$key;
                            break;
                        }
                        case 'float': {
                            $this->$key = (float) $this->$key;
                            break;
                        }
                        case 'string': {
                            $this->$key = (string) $this->$key;
                            break;
                        }
                        case 'iso-date': {
                            $this->$key = new UTCDateTime($this->$key);
                            break;
                        }
                        case 'oid': {
                            $this->$key = new ObjectId($this->$key);
                            break;
                        }
                    }
                }

            }

        }
    }

    /**
     * @param null $attributes
     * @return bool
     * @throws \yii\mongodb\Exception
     */
    protected function insertInternal($attributes = null)
    {
        \Yii::info("custom insert internal called");

        $this->typeCast();

        if (!$this->beforeSave(true)) {
            return false;
        }

        $values = $this->getDirtyAttributes($attributes);
        if (empty($values)) {
            $currentAttributes = $this->getAttributes();
            foreach ($this->primaryKey() as $key) {
                \Yii::error($currentAttributes[$key]);
                if (isset($currentAttributes[$key]) ) {
                    $values[$key] = $currentAttributes[$key];
                }
            }
        }

        foreach ($values as $key => $value) {
            if( $value === null || ($value == '' && $value !== 0) ){
                unset($values[$key]);
            }
        }

        $newId = static::getCollection()->insert($values);
        if ($newId !== null) {
            $this->setAttribute('_id', $newId);
            $values['_id'] = $newId;
        }

        $changedAttributes = array_fill_keys(array_keys($values), null);
        $this->setOldAttributes($values);
        $this->afterSave(true, $changedAttributes);

        return true;
    }

    /**
     * @param null $attributes
     * @return bool|false|int
     * @throws StaleObjectException
     * @throws \yii\db\Exception
     * @throws \yii\mongodb\Exception
     */
    protected function updateInternal($attributes = null)
    {
        \Yii::info("custom update internal called");

        $this->typeCast();

        if (!$this->beforeSave(false)) {
            return false;
        }

        $values = $this->getDirtyAttributes($attributes);
        if (empty($values)) {
            $this->afterSave(false, $values);
            return 0;
        }
        $condition = $this->getOldPrimaryKey(true);
        $lock = $this->optimisticLock();
        if ($lock !== null) {
            if (!isset($values[$lock]) && $values[$lock] !== null && $values[$lock] != '' ) {
                $values[$lock] = $this->$lock + 1;
            }
            $condition[$lock] = $this->$lock;
        }

//        foreach ($values as $key => $value) {
//            if( $value === null || ($value == '' && $value !== 0) ){
//                unset($values[$key]);
//            }
//        }

        // We do not check the return value of update() because it's possible
        // that it doesn't change anything and thus returns 0.
        $rows = static::getCollection()->update($condition, $values);

        if ($lock !== null && !$rows) {
            throw new StaleObjectException('The object being updated is outdated.');
        }

        if (isset($values[$lock])) {
            $this->$lock = $values[$lock];
        }

        $changedAttributes = [];
        foreach ($values as $name => $value) {
            $changedAttributes[$name] = $this->getOldAttribute($name);
            $this->setOldAttribute($name, $value);
        }
        $this->afterSave(false, $changedAttributes);

        return $rows;
    }

    /**
     * @param $item
     * @param $type
     * @return float|int|ObjectID|UTCDateTime|null|string
     */
    protected static function cast($item, $type){
        switch ($type) {
            case 'int': {
                $value = (int) $item;
                break;
            }
            case 'float': {
                $value = (float) $item;
                break;
            }
            case 'string': {
                $value = (string) $item;
                break;
            }
            case 'oid': {
                $value = new ObjectID($item);
                break;
            }
            case 'persian-to-iso-date': {
                $dateString = PersianHelper::faDigitToEnDigit($item);
                $dateStringExploded = explode('/', $dateString);
                $dateGre = implode('-',
                    JalaliDateHelper::jalali_to_gregorian($dateStringExploded[0],
                        $dateStringExploded[1],
                        $dateStringExploded[2]
                    ));

                $value = new UTCDatetime(strtotime($dateGre . ' 00:00:00') * 1000);

                break;
            }
            default : {
                $value = null;
            }
        }

        return $value;
    }

    /**
     * @return array|bool
     */
    protected function createConditions(){

        $matchConditions = array();

        foreach ($this->attributes as $key => $value) {
            if( $value && !empty($value)) {
                if( in_array( $key, array_keys($this->dataTypes()) ) ) {
                    $value = self::cast($value, $this->dataTypes()[$key]);
                }

                if( in_array( $key, array_keys($this->customConditions()) ) ) {
                    $value = call_user_func($this->customConditions()[$key], $value);
                }

                if( !empty($value) ) {
                    $matchConditions[$key] = $value;
                }
            }
        }

        if( count($matchConditions) > 0 ) {
            return $matchConditions;
        } else {
            return false;
        }
    }

    /**
     * @return array
     */
    protected function customConditions(){
        return [];
    }

    /**
     * @return array
     */
    protected function aggr(){
        return [];
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param $params
     * @param $collection
     * @return ArrayDataProvider
     * @throws \yii\mongodb\Exception
     */
    public function searchAggregate($params, $collection)
    {
        $collection = \Yii::$app->mongodb->getCollection($collection);

        $aggregateSection = $this->aggr();

        if (!$this->load($params)) {
            $activity = $collection->aggregate($aggregateSection);

            return new ArrayDataProvider([
                'allModels' => MongoCastHelper::magicCast($activity)
            ]);
        }

        /*if( $matchConditions = $this->createConditions() ) {
            array_push($aggregateSection, ['$match' => $matchConditions]);
        }*/

        if( $matchConditions = $this->createConditions() ) {
            $wholeAggr = [['$match' => $matchConditions]];
            $aggregateSection = array_merge($wholeAggr, $aggregateSection);
        }

        $activity = $collection->aggregate($aggregateSection);

        return new ArrayDataProvider([
            'allModels' => MongoCastHelper::magicCast($activity),
        ]);
    }
    
}
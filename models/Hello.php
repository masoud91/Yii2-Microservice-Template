<?php

namespace micro\models;

use Yii;
use micro\components\mongodb\ActiveRecord;
use micro\components\behaviors\MongoDateBehavior;

/**
 * Class Hello
 * @package micro\models
 *
 * @property \MongoDB\BSON\ObjectID|string $_id
 * @property string $name
 * @property mixed $status
 * @property mixed $cdt
 * @property mixed $udt
 */
class Hello extends ActiveRecord
{
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;

    /**
     * @inheritdoc
     */
    public static function collectionName()
    {
        return 'hello';
    }

    /**
     * @inheritdoc
     */
    public function attributes()
    {
        return [
            '_id',
            'name',
            'status',
            'cdt',
            'udt'
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [[
                '_id',
                'name',
                'status',
                'cdt',
                'udt'
            ], 'safe'],

            [['name'], 'required'],

            ['status', 'in', 'range' => [self::STATUS_INACTIVE, self::STATUS_ACTIVE]],
            ['status', 'default', 'value' => self::STATUS_ACTIVE]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            '_id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'name'),
            'status' => Yii::t('app', 'status'),
            'cdt' => Yii::t('app', 'cdt'),
            'udt' => Yii::t('app', 'udt')
        ];
    }

    /**
     * @return array
     */
    public function dataTypes()
    {
        return [
            'status' => 'int'
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => MongoDateBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['cdt', 'udt'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['udt']
                ]
            ]
        ];
    }
}
<?php

namespace micro\controllers;

use micro\models\Hello;
use yii\rest\Controller;

class HelloController extends Controller
{
    /**
     * @return array
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        unset($behaviors['rateLimiter']);
        return $behaviors;
    }

    /**
     * @return array
     */
    public function actionWorld()
    {
        return ['foo' => 'bar'];
    }

    /**
     * @return array
     */
    public function actionTest(){
        return ['foo' => (new Hello())->foo()];
    }
}
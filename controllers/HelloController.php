<?php

namespace micro\controllers;

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
}

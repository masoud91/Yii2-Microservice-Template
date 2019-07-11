<?php

use Codeception\Util\Debug;
use micro\models\Hello;

class HelloApiTest extends \Codeception\Test\Unit
{
    /**
     * @var UnitTester
     */
    protected $tester;
    
    protected function _before() {
    }

    protected function _after() {
    }

    // tests
    public function testHelloModel() {
        Debug::debug('we can print debug notes like this. but run codecept wit --debug flag to show it');

        $I = $this->tester;

        $model = new Hello();
        $model->name = 'world';
        $model->save();


        $I->assertEquals('world', $model->name);

        $I->seeRecord('micro\models\Hello', ['name' => 'world']);

    }
}
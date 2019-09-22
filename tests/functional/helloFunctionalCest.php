<?php 

class helloFunctionalCest
{
    public function _before(FunctionalTester $I)
    {
    }

    // tests
    public function testHelloApi(FunctionalTester $I)
    {
        $I->sendGET('hello/world');
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);
    }
}

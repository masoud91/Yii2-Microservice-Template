<?php 

class helloApiCest
{
    public function _before(ApiTester $I)
    {
    }

    // tests
    public function testHelloApi(ApiTester $I)
    {
        $I->sendGET('hello/world');
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);
    }
}

<?php

use Behat\Behat\Context\Context;

/**
 * Defines application features from the specific context.
 *
 * @property $client \GuzzleHttp\Client
 * @property $response \GuzzleHttp\Psr7\Response
 */
class FeatureContext implements Context
{
    private $config;
    private $baseUrl;

    /** @var \GuzzleHttp\Client $client */
    private $client;

    /** @var \GuzzleHttp\Psr7\Response $response */
    private $response;

    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
        $this->config = require('config.php');
        $this->baseUrl = $this->config['baseURL'];

        $this->client = new GuzzleHttp\Client([
            'base_uri' => $this->baseUrl,
            'http_errors' => false
        ]);
    }

    /**
     * @When /^I issue a "([^"]*)" request to "([^"]*)"$/
     *
     * @param $verb
     * @param $address
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function iIssueARequestTo($verb, $address)
    {
        $verb = strtolower($verb);
        $this->response = $this->client->request($verb, $address);
    }

    /**
     * @Then The Response Code will be :arg1
     *
     * @param $code
     * @throws Exception
     */
    public function theResponseCodeWillBe($code)
    {
        $statusCode = $this->response->getStatusCode();
        if( $statusCode != $code ) {
            throw new Exception("Invalid Response Code. Expected: $code got: $statusCode");
        }
    }

    /**
     * @Then /^The response key "([^"]*)" has value "([^"]*)"$/
     *
     * @param $key
     * @param $value
     * @throws Exception
     */
    public function theResponseKeyHasValue($key, $value)
    {
        $responseBody = json_decode($this->response->getBody(), true);
        if( !isset($responseBody[$key]) ) {
            throw new Exception("Response does not have $key key");
        }

        if( $responseBody[$key] != $value ) {
            throw new Exception("Expected $key : $value, but it is $key $responseBody[$key]");
        }
    }
}

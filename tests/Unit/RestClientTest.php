<?php

namespace Tests\Unit;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\RestClient;

/**
 * @property RestClient restClient
 */
class RestClientTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->restClient = new RestClient();
    }


    public function testResponseFromApiWithValidCredentials()
    {
        $defaultResponseCode = 200;
        $defaultContentSize = 21;

        $this->restClient->requestApi($defaultContentSize);
        $response = $this->restClient->getResponse();

        $this->assertEquals($defaultResponseCode, $response['status']);
        $this->assertIsJson($response['content']);
    }


    /**
     * @expectedException \Exception
     */
    public function testResponseFromApiWithInvalidCredentials()
    {
        $defaultContentSize = 21;

        Config::shouldReceive('get')
            ->once()
            ->with('constants.API_URL')
            ->andReturn('asdasasd');

        Config::shouldReceive('get')
            ->once()
            ->with('constants.API_HEADER')
            ->andReturn('asdasdasdasdasdasdasdsda');

        $this->restClient->requestApi($defaultContentSize);
    }

    /**
     * @param $data
     */
    protected function assertIsJson($data)
    {
        $this->assertEquals(0, json_last_error());
    }


}
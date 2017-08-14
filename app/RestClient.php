<?php

/*
|--------------------------------------------------------------------------
| Rest Client for itv api
|--------------------------------------------------------------------------
|
| This class is responsible for talking to the itv rest api and
| and returning the response.
|
*/

namespace App;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Mockery\Exception;

/**
 * @property Client client
 */
class RestClient extends Model
{

    const defaultContentSize = 21;
    private $response;

    /**
     * RestClient constructor.
     */
    public function __construct()
    {
        $this->client = new Client();
    }


    /**
     * @return array|string
     */
    public function requestApi()
    {
        try
        {
            $url = Config::get('constants.API_URL') . '&size=' . self::defaultContentSize;
            $header = ['Accept' => Config::get('constants.API_HEADER')];
            log::info('Calling api.....');
            $request = $this->client->get($url, ['headers' => $header]);
            $response = $request->getBody()->getContents();
            log::debug('Api returned status code ' . $request->getStatusCode() . ' with response ' . $response);
            $this->setResponse($request->getStatusCode(), $response);
        }
        catch (RequestException $error)
        {
            $errorCode = $error->getResponse()->getStatusCode();
            $errorContent = $error->getResponse()->getBody();
            log::error('Error returned from api with http status code ' . $errorCode . 'with body ' . $errorContent);
            throw new Exception('Unable to fetch information from API');
        }
    }


    /**
     * @param $responseCode string
     * @param $responseMessage string
     * @return array
     */
    public function setResponse($responseCode, $responseMessage)
    {
        $response = [
            'status' => $responseCode,
            'content' => $responseMessage
        ];
        $this->response = $response;
    }

    /**
     * @return mixed
     */
    public function getResponse()
    {
        return $this->response;
    }


}
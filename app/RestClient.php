<?php

namespace App;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\TransferStats;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Mockery\Exception;

/**
 * @property Client client
 * @property ContentFeed $contentFeed
 */
class RestClient extends Model
{

    const defaultContentSize = 21;

    /**
     * RestClient constructor.
     */
    public function __construct()
    {
        $this->client = new Client();
        $this->contentFeed = new ContentFeed();
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
            return $this->getResponse($request->getStatusCode(), $response);
        }
        catch (RequestException $error)
        {
            $errorCode = $error->getResponse()->getStatusCode();
            $errorContent = $error->getResponse()->getBody();
            log::error('Error returned from api with http status code ' . $errorCode . 'with body ' . $errorContent);
            return $this->getResponse($errorCode, $errorContent);
        }
    }


    /**
     * @return array
     */
    public function getParsedApiResponse() {
        $rawApiData = $this->requestApi();
        if($rawApiData['status'] == '200') {
            log::info('Parsing response from API....');
            $this->contentFeed->setFeed(json_decode($rawApiData['content']));
        }
        return $this->contentFeed->getFeed();
    }


    /**
     * @param $responseCode string
     * @param $responseMessage string
     * @return array
     */
    public function getResponse($responseCode, $responseMessage)
    {
        return [
            'status' => $responseCode,
            'content' => $responseMessage
        ];
    }

}
<?php

/*
|--------------------------------------------------------------------------
| Middle layer in between the controller and rest client
|--------------------------------------------------------------------------
|
| All the business logic goes in this class.
| This class parses the api response
| and returns the data to the controller
|
*/

namespace App;

use Illuminate\Support\Facades\Config;
use Mockery\Exception;


class ContentFeed
{
    private $restClient;
    private $feed = [];
    const defaultImageQuality = 80;
    const heroImageWidth = 1200;
    const smallImageWidth = 320;
    const defaultContentSize = 21;

    public function __construct($contentSize)
    {
        $this->restClient = new RestClient();
        $this->restClient->requestApi($contentSize);
        $this->setFeed();
    }

    /**
     * @return void
     */
    public function setFeed()
    {
        $apiResponse = $this->restClient->getResponse();
        $content = json_decode($apiResponse['content']);
        if(sizeof($content->_embedded->programmes) < self::defaultContentSize)
        {
            throw new Exception('Not enough content returned from api');
        } else {
            foreach ($content->_embedded->programmes as $key => $each_programme) {
                $image = $each_programme->_embedded->latestProduction->_links->image->href;
                $this->feed[$key] = [
                    'id' => $each_programme->id,
                    'title' => $each_programme->title,
                    'heroImage' => $this->getEffectiveUrl($image, self::heroImageWidth),
                    'smallImage' => $this->getEffectiveUrl($image, self::smallImageWidth),
                    'description' => $each_programme->synopses->ninety
                ];
            }
        }
    }

    /**
     * @return array
     */
    public function getFeed()
    {
        return $this->feed;
    }


    /**
     * @param $imageUrl string
     * @param $width integer
     * @return string
     */
    public function getEffectiveUrl($imageUrl, $width)
    {
        $urlParts = parse_url($imageUrl);
        parse_str($urlParts['query'], $params);
        $requiredParams = [];

        foreach($params as $key => $eachParam)
        {
            if($key == 'w') {
                $requiredParams[$key] = $width;
            }
            if($key == 'q') {
                $requiredParams[$key] = self::defaultImageQuality;
            }
            if($key == 'productionId') {
                $requiredParams[$key] = $eachParam;
            }
        }

        $paramString = http_build_query($requiredParams);
        return Config::get('constants.ASSETS'). '?' . $paramString;
    }

}
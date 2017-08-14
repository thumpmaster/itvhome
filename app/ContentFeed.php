<?php

namespace App;


use Illuminate\Support\Facades\Config;

class ContentFeed
{

    private $feed = [];
    const defaultImageQuality = 80;
    const heroImageWidth = 1200;
    const smallImageWidth = 320;

    /**
     * @param $content
     */
    public function setFeed($content)
    {
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

    /**
     * @return array
     */
    public function getFeed()
    {
        return $this->feed;
    }


    /**
     * @param $imageUrl
     * @param $width
     * @return string
     */
    private function getEffectiveUrl($imageUrl, $width)
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
<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\RestClient;

/**
 * @property  RestClient
 */
class HomePageController extends Controller
{
    const defaultContentBlocks = 3;
    const defaultRecommendedContentSize = 6;

    public function index()
    {
        $restClient = new RestClient;
        $contentFromApi = $restClient->getParsedApiResponse();
        return view('pages.homepage', ['content' => $this->getContentWall($contentFromApi)]);
    }


    /**
     * All the presentation logic goes in this method
     *
     * @param $content
     * @return array
     */
    private function getContentWall($content)
    {
        $contentWall = [];
        $leaderContent = array_slice($content, 0, self::defaultContentBlocks);
        $recommendedContent = array_slice($content, self::defaultContentBlocks);

        $section = 0;
        $rowNumber = 0;
        foreach ($recommendedContent as $key => $eachSoldierContent) {
            $contentWall[$section]['recommendedContent'][$rowNumber][] = $eachSoldierContent;

            /* increase row number when number of content blocks reaches 3 */
            if(($key+1) % self::defaultContentBlocks == 0) {
               $rowNumber++;
            }

            /* set the row number back to 1 and increase section when number of content blocks reaches 6 */
            if(($key+1) % self::defaultRecommendedContentSize == 0)
            {
                $contentWall[$section]['leaderContent'] = $leaderContent[$section];
                $section++;
                $rowNumber = 0;
            }
        }
        return $contentWall;
    }
}

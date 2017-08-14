<?php

namespace Tests\Unit;
use App\ContentFeed;
use Illuminate\Support\Facades\Config;
use ReflectionClass;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\RestClient;


/**
 * @property ContentFeed feed
 */
class ContentFeedTest extends TestCase
{
    const defaultContentSize = 21;
    private $hostName = 'https://secure-mercury.itv.com';

    public function setUp()
    {
        parent::setUp();
        $this->feed = new ContentFeed(self::defaultContentSize);
    }


    public function testContentFeedReturned()
    {
        $contentReturned = $this->feed->getFeed();
        $this->assertEquals(self::defaultContentSize, sizeof($contentReturned));
    }



    public function testFieldsInsideContentFeed()
    {
        $contentReturned = $this->feed->getFeed();
        foreach ($contentReturned as $eachContent)
        {
            $this->assertNotNull($eachContent['id']);
            $this->assertNotNull($eachContent['title']);
            $this->assertNotNull($eachContent['heroImage']);
            $this->assertNotNull($eachContent['smallImage']);
            $this->assertNotNull($eachContent['description']);
        }
    }


    public function testImageEffectiveUrl()
    {
        $mockImageUrl = $this->hostName . '/dotcom/production/image?q={quality}&format={image_format}&w={width}&h={height}&blur={blur}&bg={bg}&productionId=1%2F0694%2F9228%23001';
        $mockImageWidth = 1200;

        $imageUrlReturned = $this->feed->getEffectiveUrl($mockImageUrl, $mockImageWidth);
        $imageUrlExpected = $this->hostName . '/dotcom/production/image?q=80&w=1200&productionId=1%2F0694%2F9228%23001';

        $this->assertEquals($imageUrlExpected, $imageUrlReturned);

    }



}
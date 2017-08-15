<?php

use Laracasts\Integrated\Extensions\Laravel as IntegrationTest;

class HomePageTest extends IntegrationTest
{

    /** @test */
    public function testHomePage()
    {
        $this->visit('/');
    }

    /** @test */
    public function testHomePageWithTextExists()
    {
        $this->visit('/')
            ->see('itv Homepage');
    }


    /** @test */
    public function testNumberOfHeroBannerOnHomePage()
    {
        $defaultNumberOfHeroBanners = 3;
        $this->visit('/')
            ->see('itv Homepage')
            ->countElements('#banner', $defaultNumberOfHeroBanners);
    }


    /** @test */
    public function testNumberOfSmallBannersOnHomePage()
    {
        $defaultNumberOfSmallBanners = 18;
        $this->visit('/')
            ->see('itv Homepage')
            ->countElements('image featured', $defaultNumberOfSmallBanners);
    }


    /** @test */
    public function testTitlesOnHeroBanners()
    {
        $defaultNumberOfHeroBanners = 3;
        $this->visit('/')
            ->see('itv Homepage')
            ->countElements('#banner header h2', $defaultNumberOfHeroBanners);
    }

    /** @test */
    public function testDescriptionOnHeroBanners()
    {
        $defaultNumberOfHeroBanners = 3;
        $this->visit('/')
            ->see('itv Homepage')
            ->countElements('#banner header p', $defaultNumberOfHeroBanners);
    }


    /** @test */
    public function testTitlesOnSmallBanners()
    {
        $defaultNumberOfSmallBanners = 18;
        $this->visit('/')
            ->see('itv Homepage')
            ->countElements('.box header h3', $defaultNumberOfSmallBanners);
    }


    /** @test */
    public function testDescriptionOnSmallBanners()
    {
        $defaultNumberOfSmallBanners = 18;
        $this->visit('/')
            ->see('itv Homepage')
            ->countElements('.box header p', $defaultNumberOfSmallBanners);
    }


    /**
     * Verify the number of dom elements
     * @param  string   $selector the dom selector (jquery style)
     * @param  int      $number   how many elements should be present in the dom
     * @return $this
     */
    public function countElements($selector, $number)
    {
        $this->assertCount($number, $this->crawler->filter($selector));
        return $this;
    }

}

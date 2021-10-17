<?php

namespace Tests\Http\Controllers;

use App\Http\Controllers\readCsv;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class readCsvTest extends TestCase
{
    /**
     * Test basic path launch 200 ok loading
     *
     * @return void
     */
    public function testRootOk()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    /**
     * Test page  launch 200 ok loading
     *
     * @return void
     */
    public function testPageTestOk(){

        $response = $this->get('/smallWorldTestHome');

        $response->assertStatus(200);
    }

    /**
     * Test verify if csv files exist for read
     *
     * @return void
     */
    public function testFilesCsvExist()
    {
        $pathCsvLeagueOfLegend = resource_path('csvFiles/league_of_legends.csv');
        $pathCsvValorant = resource_path('csvFiles/valorantGameOk.csv');
        $this->assertTrue(file_exists($pathCsvLeagueOfLegend));
        $this->assertTrue(file_exists($pathCsvValorant));

    }


    /**
     * Test if cache have data for calculate ranking
     *
     * @return void
     */
    public function testCalculateTotalRankingVerifyCacheLoaded()
    {
        $resultsGames = Cache::get('resultsGameLines');
        $this->assertIsArray($resultsGames,'cache invalid or not loaded');
    }

    /**
     * Test if cache have keys for calculate ranking
     *
     * @return void
     */
    public function testCalculateTotalRankingVerifyKeys()
    {
        $resultsGames = Cache::get('resultsGameLines');
        $this->assertArrayHasKey('league',$resultsGames,'basic key league for first game not found');
        $this->assertArrayHasKey('valorant',$resultsGames,'basic key valorant for second game not found');

    }

    /**
     * Test if loading excel from file system load and read or some file get error
     *
     * @return void
     */
    public function testLoadDataFromExcel()
    {
        $readController = new readCsv();
        $replyJson = (string) ($readController->loadDataFromExcel());
        $this->assertStringNotContainsString('fail',$replyJson,'Error reading some file');
    }
}

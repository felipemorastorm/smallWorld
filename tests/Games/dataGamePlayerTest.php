<?php

namespace Tests\Games;

use App\Games\dataGamePlayer;
use Tests\TestCase;

class dataGamePlayerTest extends TestCase
{

    /**
     * Test score for player top and winner diferent cases
     *
     * @return void
     */
    public function testCalculationLeagueScoreOkForTopPlayerAndWinner(){
        //player 1;nick1;Team A;true;T;10;5;2;2000;200
        $dataGamePlayer = new dataGamePlayer();
        $dataGamePlayer->setLinePlayerGameData(
            'league',
            'player 1',
            'nick1',
            'Team A',
            true,
            'T',
            10,
            5,
            0,
            2000,
            200);

        $dataGamePlayer->calculateScoreByGameAndPlayer();
        $this->assertEquals($dataGamePlayer->linePlayerGameData['score'], 20);

        $dataGamePlayer = new dataGamePlayer();
        $dataGamePlayer->setLinePlayerGameData(
            'league',
            'player 1',
            'nick1',
            'Team A',
            true,
            'T',
            10,
            5,
            2,
            3000,
            100);

        $dataGamePlayer->calculateScoreByGameAndPlayer();
        $this->assertEquals($dataGamePlayer->linePlayerGameData['score'], 22.4);

        $dataGamePlayer = new dataGamePlayer();
        $dataGamePlayer->setLinePlayerGameData(
            'league',
            'player 1',
            'nick1',
            'Team A',
            true,
            'T',
            0,
            2,
            2,
            3000,
            100);

        $dataGamePlayer->calculateScoreByGameAndPlayer();
        $this->assertEquals($dataGamePlayer->linePlayerGameData['score'], 21);

    }
    /**
     * Test score for player top and loser diferent cases
     *
     * @return void
     */
    public function testCalculationLeagueScoreOkForTopPlayerAndLoser(){
        //player 1;nick1;Team A;true;T;10;5;2;2000;200
        $dataGamePlayer = new dataGamePlayer();
        $dataGamePlayer->setLinePlayerGameData(
            'league',
            'player 1',
            'nick1',
            'Team A',
            false,
            'T',
            10,
            5,
            0,
            2000,
            200);

        $dataGamePlayer->calculateScoreByGameAndPlayer();
        $this->assertEquals($dataGamePlayer->linePlayerGameData['score'], 10);
    }
    /*
     * E.g. a player playing as a Mid with 10 kills,
     * 5 deaths and no assists will be granted with 2 KDA points ((10 + 0) / 5 ).
     * Aggregating 2000 damage deal and 200 of healing (2 + 2000*0.003(6) + 200*0.001),
     *  the final result is 10 rating points.*/
    /**
     * Test score for player medium and winner diferent cases
     *
     * @return void
     */
    public function testCalculationLeagueScoreOkForMediumPlayerAndWinner()
    {
        $dataGamePlayer = new dataGamePlayer();
        $dataGamePlayer->setLinePlayerGameData(
            'league',
            'player 1',
            'nick1',
            'Team A',
            true,
            'M',
            10,
            5,
            0,
            2000,
            200);
        $dataGamePlayer->calculateScoreByGameAndPlayer();
        $this->assertEquals($dataGamePlayer->linePlayerGameData['score'], 20);
    }
    /**
     * Test score for player medium and loser diferent cases
     *
     * @return void
     */
    public function testCalculationLeagueScoreOkForMediumPlayerAndLoser()
    {
        $dataGamePlayer = new dataGamePlayer();
        $dataGamePlayer->setLinePlayerGameData(
            'league',
            'player 1',
            'nick1',
            'Team A',
            false,
            'M',
            10,
            5,
            0,
            2000,
            200);
        $dataGamePlayer->calculateScoreByGameAndPlayer();
        $this->assertEquals($dataGamePlayer->linePlayerGameData['score'], 10);
    }
    /**
     * Test score for player bottom and winner diferent cases
     *
     * @return void
     */
    public function testCalculationLeagueScoreOkForBottomPlayerAndWinner()
    {
        $dataGamePlayer = new dataGamePlayer();
        $dataGamePlayer->setLinePlayerGameData(
            'league',
            'player 1',
            'nick1',
            'Team A',
            true,
            'B',
            10,
            5,
            0,
            2000,
            200);
        $dataGamePlayer->calculateScoreByGameAndPlayer();
        $this->assertEquals($dataGamePlayer->linePlayerGameData['score'], 20);
    }
    /**
     * Test score for player bottom and loser diferent cases
     *
     * @return void
     */
    public function testCalculationLeagueScoreOkForBottomPlayerAndLoser()
    {
        $dataGamePlayer = new dataGamePlayer();
        $dataGamePlayer->setLinePlayerGameData(
            'league',
            'player 1',
            'nick1',
            'Team A',
            false,
            'B',
            10,
            5,
            0,
            2000,
            200);
        $dataGamePlayer->calculateScoreByGameAndPlayer();
        $this->assertEquals($dataGamePlayer->linePlayerGameData['score'], 10);
    }
    /**
     * Test score for player jungle and winner diferent cases
     *
     * @return void
     */
    public function testCalculationLeagueScoreOkForJunglePlayerAndWinner()
    {
        $dataGamePlayer = new dataGamePlayer();
        $dataGamePlayer->setLinePlayerGameData(
            'league',
            'player 1',
            'nick1',
            'Team A',
            true,
            'J',
            10,
            5,
            0,
            2000,
            200);
        $dataGamePlayer->calculateScoreByGameAndPlayer();
        $this->assertEquals($dataGamePlayer->linePlayerGameData['score'], 20);
    }
    /**
     * Test score for player jungle and loser diferent cases
     *
     * @return void
     */
    public function testCalculationLeagueScoreOkForJunglePlayerAndLoser()
    {
        $dataGamePlayer = new dataGamePlayer();
        $dataGamePlayer->setLinePlayerGameData(
            'league',
            'player 1',
            'nick1',
            'Team A',
            false,
            'J',
            10,
            5,
            0,
            2000,
            200);
        $dataGamePlayer->calculateScoreByGameAndPlayer();
        $this->assertEquals($dataGamePlayer->linePlayerGameData['score'], 10);
    }
    /**
     * Test score for player support and winner diferent cases
     *
     * @return void
     */
    public function testCalculationLeagueScoreOkForSupportPlayerAndWinner()
    {
        $dataGamePlayer = new dataGamePlayer();
        $dataGamePlayer->setLinePlayerGameData(
            'league',
            'player 1',
            'nick1',
            'Team A',
            true,
            'S',
            10,
            5,
            0,
            2000,
            200);
        $dataGamePlayer->calculateScoreByGameAndPlayer();
        $this->assertEquals($dataGamePlayer->linePlayerGameData['score'], 20);
    }
    /**
     * Test score for player support and loser diferent cases
     *
     * @return void
     */
    public function testCalculationLeagueScoreOkForSupportPlayerAndLoser()
    {
        $dataGamePlayer = new dataGamePlayer();
        $dataGamePlayer->setLinePlayerGameData(
            'league',
            'player 1',
            'nick1',
            'Team A',
            false,
            'S',
            10,
            5,
            0,
            2000,
            200);
        $dataGamePlayer->calculateScoreByGameAndPlayer();
        $this->assertEquals($dataGamePlayer->linePlayerGameData['score'], 10);

        $dataGamePlayer->setLinePlayerGameData(
            'league',
            'player 1',
            'nick1',
            'Team A',
            false,
            'S',
            0,
            2,
            10,
            1000,
            2000);
        $dataGamePlayer->calculateScoreByGameAndPlayer();
        $this->assertEquals($dataGamePlayer->linePlayerGameData['score'], 66);

        $dataGamePlayer->setLinePlayerGameData(
            'league',
            'player 1',
            'nick1',
            'Team A',
            false,
            'S',
            1,
            2,
            2,
            200,
            200);
        $dataGamePlayer->calculateScoreByGameAndPlayer();
        $this->assertEquals($dataGamePlayer->linePlayerGameData['score'], 7.7);
    }

    /**
     * Check integrity all values correctly setted in constructor
     *
     * @return void
     */
    public function testCheckIntegritySettingPlayer()
    {
        $dataGamePlayer = new dataGamePlayer();
        $dataGamePlayer->setLinePlayerGameData(
            'league',
            'player 1',
            'nick1',
            'Team A',
            false,
            'S',
            10,
            5,
            0,
            2000,
            200);

        $this->assertEquals($dataGamePlayer->linePlayerGameData['game'], 'league');
        $this->assertEquals($dataGamePlayer->linePlayerGameData['player'], 'player 1');
        $this->assertEquals($dataGamePlayer->linePlayerGameData['nick'], 'nick1');
        $this->assertEquals($dataGamePlayer->linePlayerGameData['team'], 'Team A');
        $this->assertEquals($dataGamePlayer->linePlayerGameData['winner'], false);
        $this->assertEquals($dataGamePlayer->linePlayerGameData['position'], 'S');
        $this->assertEquals($dataGamePlayer->linePlayerGameData['kills'], 10);
        $this->assertEquals($dataGamePlayer->linePlayerGameData['deaths'], 5);
        $this->assertEquals($dataGamePlayer->linePlayerGameData['assists'], 0);
        $this->assertEquals($dataGamePlayer->linePlayerGameData['damage'], 2000);
        $this->assertEquals($dataGamePlayer->linePlayerGameData['heal'], 200);

    }
    /**
     * Check integrity fails when values are not valid on constructor
     *
     * @return void
     */
    public function testFailureCheckIntegritySettingPlayer(){
        $dataGamePlayer = new dataGamePlayer();
        $dataGamePlayer->setLinePlayerGameData(
            '',
            'player 1',
            'nick1',
            'Team A',
            false,
            'S',
            10,
            5,
            0,
            2000,
            200);
        $this->assertEquals($dataGamePlayer->linePlayerGameData, null,'Method set is setted with empty game value!');


        $dataGamePlayer->setLinePlayerGameData(
            'league',
            '',
            'nick1',
            'Team A',
            false,
            'S',
            10,
            5,
            0,
            2000,
            200);
        $this->assertEquals($dataGamePlayer->linePlayerGameData, null,'Method set is setted with empty player value!');

        $dataGamePlayer->setLinePlayerGameData(
            'league',
            'Player a',
            '',
            'Team A',
            false,
            'S',
            10,
            5,
            0,
            2000,
            200);
        $this->assertEquals($dataGamePlayer->linePlayerGameData, null,'Constructor dataGamePlayer is setted with empty nick value!');

        $dataGamePlayer->setLinePlayerGameData(
            'league',
            'Player a',
            'nick-me',
            '',
            false,
            'S',
            10,
            5,
            0,
            2000,
            200);
        $this->assertEquals($dataGamePlayer->linePlayerGameData, null,'Constructor dataGamePlayer is setted with empty team value!');


    }
}

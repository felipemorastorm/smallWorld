<?php
namespace App\Games;
/*
 * Top (T)	(Kills + Assists) / Deaths	0.03	0.01
Bottom (B)	(Kills + Assists) / Deaths	0.03	0.01
Mid (M)	(Kills + Assists) / Deaths	0.03	0.01
Jungle (J)	(Kills + Assists) / Deaths	0.02	0.02
Support (S)	(Kills + Assists) / Deaths	0.01	0.03
*/
/**
 *
 */
const _damageDeal = array('T'=>0.03,'B'=>0.03,'M'=>0.03,'J'=>'0.02','S'=>0.01);
/**
 *
 */
const _healDeal   = array('T'=>0.01,'B'=>0.01,'M'=>0.01,'J'=>'0.02','S'=>0.03);

/**
 * Class dataGamePlayer
 * @package App\Games
 */
class dataGamePlayer
{
    /**
     * dataGamePlayer constructor.
     */
    public $linePlayerGameData ;

    /**
     * @param $game
     * @param $player
     * @param $nick
     * @param $team
     * @param $winner
     * @param $position
     * @param $kills
     * @param $deaths
     * @param $assists
     * @param $damage
     * @param $heal
     * constructor , set all params of class
     */
    public function setLinePlayerGameData($game, $player, $nick, $team, $winner, $position, $kills, $deaths, $assists, $damage, $heal){
        $this->linePlayerGameData['player']   = $player;
        $this->linePlayerGameData['nick']     = $nick;
        $this->linePlayerGameData['team']     = $team;
        $this->linePlayerGameData['winner']   = $winner;
        $this->linePlayerGameData['position'] = $position;
        $this->linePlayerGameData['kills']    = $kills;
        $this->linePlayerGameData['deaths']   = $deaths;
        $this->linePlayerGameData['assists']  = $assists;
        $this->linePlayerGameData['damage']   = $damage;
        $this->linePlayerGameData['heal']     = $heal;
        $this->linePlayerGameData['game']     = $game;
        $this->linePlayerGameData['score']     = null;
    }

    /**
     * @param $game
     * @param $player
     * @param $nick
     * @param $team
     * @param $kills
     * @param $deaths
     * set the constructor if game have less params for second game
     */
    public function setLinePlayerGameDataB($game, $player, $nick, $team, $kills, $deaths)
    {
        $this->linePlayerGameData['player']   = $player;
        $this->linePlayerGameData['nick']     = $nick;
        $this->linePlayerGameData['team']     = $team;
        $this->linePlayerGameData['kills']    = $kills;
        $this->linePlayerGameData['deaths']   = $deaths;
        $this->linePlayerGameData['game']     = $game;
        $this->linePlayerGameData['score']     = null;

    }

    /**
     * @return mixed
     * Calculate her score after constructor setted call saving her value into class value score himself
     * distinction for score formule is done depend of game
     */
    public function calculateScoreByGameAndPlayer()
        {

            if ($this->linePlayerGameData['game'] == 'league') {
                /*
                 * E.g. a player playing as a Mid with 10 kills, 5 deaths and no assists will be granted with 2 KDA points
                 * ((10 + 0) / 5 ).
                 * Aggregating 2000 damage deal and 200 of healing
                 * (2 + 2000*0.03 + 200*0.01),
                 * the final result is 10 rating points.*/
                $score = 0;
                $kda = ($this->linePlayerGameData['kills'] + $this->linePlayerGameData['assists']) / $this->linePlayerGameData['deaths'];
                $damageDeal = _damageDeal[$this->linePlayerGameData['position']] * $this->linePlayerGameData['damage'];
                $healDeal = _healDeal[$this->linePlayerGameData['position']] * $this->linePlayerGameData['heal'];
                $score = $kda + $damageDeal + $healDeal;
                if ($this->linePlayerGameData['winner'] == true) {
                    $score += 10;
                }
            }else{
                //valorant
                $score =$kda =  $this->linePlayerGameData['kills'] / $this->linePlayerGameData['deaths'];
            }
            $this->linePlayerGameData['score'] = $score;
            return $this->linePlayerGameData;

        }

    /**
     *
     */
    public function getWinnerValorant(){

    }

}

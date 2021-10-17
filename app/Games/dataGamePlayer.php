<?php
namespace App\Games;
/*
 * Top (T)	(Kills + Assists) / Deaths	0.03	0.01
Bottom (B)	(Kills + Assists) / Deaths	0.03	0.01
Mid (M)	(Kills + Assists) / Deaths	0.03	0.01
Jungle (J)	(Kills + Assists) / Deaths	0.02	0.02
Support (S)	(Kills + Assists) / Deaths	0.01	0.03
*/

use PHPUnit\Util\Exception;

/**
 *
 */
const _damageDeal = array('T'=>0.003,'B'=>0.003,'M'=>0.003,'J'=>0.002,'S'=>0.001);
/**
 *
 */
const _healDeal   = array('T'=>0.01,'B'=>0.01,'M'=>0.01,'J'=>0.02,'S'=>0.03);
/**
 *
 */
const _validTypePlayer   = array('T','B','M','J','S');

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
        $isValid = $this->checkIntegrity($game, $player, $nick, $team, $winner, $position, $kills, $deaths, $assists, $damage, $heal);
        if($isValid===true) {
            $this->linePlayerGameData['player'] = $player;
            $this->linePlayerGameData['nick'] = $nick;
            $this->linePlayerGameData['team'] = $team;
            $this->linePlayerGameData['winner'] = $winner;
            $this->linePlayerGameData['position'] = $position;
            $this->linePlayerGameData['kills'] = $kills;
            $this->linePlayerGameData['deaths'] = $deaths;
            $this->linePlayerGameData['assists'] = $assists;
            $this->linePlayerGameData['damage'] = $damage;
            $this->linePlayerGameData['heal'] = $heal;
            $this->linePlayerGameData['game'] = $game;
            $this->linePlayerGameData['score'] = null;
        }else{
            //derive output to test message
            echo $isValid;
            return false;
        }
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
        $isValid = $this->checkIntegrity($game, $player, $nick, $team, false, 'A', $kills, $deaths);
        if($isValid===true) {
            $this->linePlayerGameData['game'] = $game;
            $this->linePlayerGameData['player'] = $player;
            $this->linePlayerGameData['nick'] = $nick;
            $this->linePlayerGameData['team'] = $team;
            $this->linePlayerGameData['kills'] = $kills;
            $this->linePlayerGameData['deaths'] = $deaths;
            $this->linePlayerGameData['score'] = null;
        }else{
            echo $isValid;die;
            return false;
        }
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
                if ($this->linePlayerGameData['deaths'] !== 0) {
                    $kda = ($this->linePlayerGameData['kills'] + $this->linePlayerGameData['assists']) / $this->linePlayerGameData['deaths'];
                } else {
                    $kda = 0;
                }
                $damageDeal = _damageDeal[$this->linePlayerGameData['position']] * $this->linePlayerGameData['damage'];
                $healDeal = _healDeal[$this->linePlayerGameData['position']] * $this->linePlayerGameData['heal'];
                $score = $kda + $damageDeal + $healDeal;
                if ($this->linePlayerGameData['winner'] == true) {
                    $score += 10;
                }

                //echo 'kda->'.$kda.'-DAMAGE'._damageDeal[$this->linePlayerGameData['position']] .'*'.$this->linePlayerGameData['damage'].'='.$damageDeal.
                //'-----HEAL'._healDeal[$this->linePlayerGameData['position']] .'*'.$this->linePlayerGameData['heal'].'='.$healDeal;
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
     * @return bool|string
     * Return true if check integrity pass ok or error message in string if some failed
     */
    public function checkIntegrity($game, $player, $nick, $team, $winner, $position, $kills, $deaths, $assists, $damage, $heal){

        if(is_string($game) && !empty($game)){

        }else{
           return 'integrity violation : game cannot be empty';
        }


        if(is_string($player) && !empty($player)){

        }else{
            return 'integrity violation : player cannot be empty';
        }

        if(is_string($nick) && !empty($nick)){

        }else{
            return 'integrity violation : nick cannot be empty';
        }

        if(is_string($team) && !empty($team)){

        }else{
            return 'integrity violation : team cannot be empty';
        }

        if(is_int($kills) &&($kills>=0)  ){

        }else{
            return 'integrity violation : kills must be integer and must be greater than 0';
        }

        if(is_int($deaths) &&($deaths>=0)  ){

        }else{
            return 'integrity violation : deaths must be integer and must be greater than 0';
        }

        //avoid check values that only avaliable in league of legends
        if($game!='valorant') {
            if(is_bool($winner)){

            }else{
                return 'integrity violation : kills must be integer and must be greater than 0';
            }

            if(is_string($position)  && in_array($position,_validTypePlayer) ){

            }else{
                return 'integrity violation : type must be type allowed';
            }

            if (is_int($assists) && ($assists >= 0)) {

            } else {
                return 'integrity violation : assists must be integer and must be greater than 0';
            }

            if (is_int($damage) && ($damage >= 0)) {

            } else {
                return 'integrity violation : damage must be integer and must be greater than 0';
            }

            if (is_int($heal) && ($heal >= 0)) {

            } else {
                return 'integrity violation : heal must be integer and must be greater than 0';
            }
        }

        return true;

    }

}

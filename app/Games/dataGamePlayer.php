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
            $this->linePlayerGameData['winner'] = (bool)$winner;
            $this->linePlayerGameData['position'] = $position;
            $this->linePlayerGameData['kills'] = $kills;
            $this->linePlayerGameData['deaths'] = (int)$deaths;
            $this->linePlayerGameData['assists'] = (int)$assists;
            $this->linePlayerGameData['damage'] = (int)$damage;
            $this->linePlayerGameData['heal'] = (int)$heal;
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
     * @param $winner

     * @param $kills
     * @param $deaths
     * set the constructor if game have less params for second game(valorant)
     */
    public function setLinePlayerGameDataB($game, $player, $nick, $team,$winner, $kills, $deaths)
    {
        $isValid = $this->checkIntegrity($game, $player, $nick, $team, (bool)$winner,null, (int)$kills, (int)$deaths,null,null,null);
        if($isValid===true) {
            $this->linePlayerGameData['game'] = $game;
            $this->linePlayerGameData['player'] = $player;
            $this->linePlayerGameData['nick'] = $nick;
            $this->linePlayerGameData['winner'] = (bool)$winner;
            $this->linePlayerGameData['team'] = $team;
            $this->linePlayerGameData['kills'] =(int) $kills;
            $this->linePlayerGameData['deaths'] =(int) $deaths;
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
            if ($this->linePlayerGameData !== null && $this->linePlayerGameData['game'] == 'league') {
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
                $this->linePlayerGameData['score'] = $score;
                //echo 'kda->'.$kda.'-DAMAGE'._damageDeal[$this->linePlayerGameData['position']] .'*'.$this->linePlayerGameData['damage'].'='.$damageDeal.
                //'-----HEAL'._healDeal[$this->linePlayerGameData['position']] .'*'.$this->linePlayerGameData['heal'].'='.$healDeal;
            }else{
                if ($this->linePlayerGameData !== null) {
                    //valorant
                    $score = $kda = $this->linePlayerGameData['kills'] / $this->linePlayerGameData['deaths'];
                    if ($this->linePlayerGameData['winner'] == true) {
                        $score += 10;
                    }
                    $this->linePlayerGameData['score'] = $score;

                }

            }
            return $this->linePlayerGameData;

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

        if(is_int((int)$kills) &&((int)$kills>=0)  ){

        }else{
            return 'integrity violation : kills must be integer and must be greater than 0';
        }

        if(is_int((int)$deaths) &&((int)$deaths>=0)  ){

        }else{
            return 'integrity violation : deaths must be integer and must be greater than 0';
        }
        if(is_bool((bool)$winner) && ( (bool)$winner==true || (bool)$winner==false) ){

        }else{
            return 'integrity violation : winner must be a boolean';
        }

        //avoid check values that only avaliable in league of legends
        if($game!='valorant') {

            if(is_string($position)  && in_array($position,_validTypePlayer) ){

            }else{
                return 'integrity violation : type must be type allowed';
            }

            if (is_int((int)$assists) && ((int)$assists >= 0)) {

            } else {
                return 'integrity violation : assists must be integer and must be greater than 0';
            }

            if (is_int((int)$damage) && ((int)$damage >= 0)) {

            } else {
                return 'integrity violation : damage must be integer and must be greater than 0';
            }

            if (is_int((int)$heal) && ((int)$heal >= 0)) {

            } else {
                return 'integrity violation : heal must be integer and must be greater than 0';
            }
        }

        return true;

    }

}

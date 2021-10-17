<?php

namespace App\Http\Controllers;

use App\Games\dataGamePlayer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;


/**
 * Class readCsv
 * @package App\Http\Controllers
 */
class readCsv extends Controller
{

    /**
     * Load the data from initial excels, just load the in the example but explain how we can do this
     * with +2 files via scandisk, this call is doing via ajax and will return via ui, if files (key) are
     * ready normal or with errors, if any exception happens we will see ok in the return reply for every file
     * and the data are saved in cache (for use later in second call or other process data, but i do separataly
     * for focus more in operations read and calculation)
     */
    public function loadDataFromExcel()
    {
        try {
            //here i put the path to the 2 files that i have , i assume what normally the process would push more files
            //then this would be done with  scandisk and read file by file
            if ( isset($_POST['valid']) && $_POST['valid'] == 1 ) {
                $pathCsvLeagueOfLegend = resource_path('csvFiles/league_of_legends.csv');
                $pathCsvValorant = resource_path('csvFiles/valorantGameOk.csv');
            } else {
                $pathCsvLeagueOfLegend = resource_path('csvFiles/league_of_legends.csv');
                $pathCsvValorant = resource_path('csvFiles/valorant.csv');
            }

            $arrayGames = array('league' => $pathCsvLeagueOfLegend, 'valorant' => $pathCsvValorant);

            $arrayResultGamesByLine = array();
            $displayInformation = '';
            foreach ($arrayGames as $key => $path) {
                $resultsGame = $this->readDataOneGame($path);

                if (is_array($resultsGame)) {
                    $arrayResultGamesByLine[$key] = $resultsGame;
                    $displayInformation .= $key . ' read ok <br>';
                } else {
                    $displayInformation .= $key . ' fail by : ' . $resultsGame . '<br>';
                }

            }

            //save data in some part with arrayReply Data, save data for trait separately
            //(if so many data maybe we need make a cron or organise how we trait data with queue/cron process)
            //or saving into ddbb results of read lines without validation
            //in this case simply we are going to save results of read line on cache
        }catch(Exception $e){
            echo ($e->getMessage());die;
        }
        Cache::put('resultsGameLines', $arrayResultGamesByLine);

        echo json_encode($displayInformation);
    }


    /**
     * @param $path the path to the csv file that have the score lines
     * @return string if file not can be read or integrity deaths and kill failthe returned string is message callback to return to ui , with ok for each file readed or fail
     * @return array with lines readed if all ok
     */
    public function readDataOneGame($path)
    {
        if (file_exists($path)) {
            //integrity check for
            $file_handle = fopen($path, 'r');
            $iterationTitle = false;
            $title = null;
            $killsTeam = array();
            $deathsTeam = array();

            //read lines by lines and put contents in var contents for save later
            //same time check and get values for kills number
            //uac : As file validation, the Team A Kills should match the Team B Deaths, and vice versa.

            while (!feof($file_handle)) {
                try {
                    $lineReaded = fgetcsv($file_handle, 0, ';');
                    //do this for avoid parse data from first header line title of game
                    if ($iterationTitle !== false) {
                        if ($title == 'LEAGUE OF LEGENDS') {
                            //postion 2 for league of legends is team, kills and death 5 and 6
                            if (isset($lineReaded[2]) && is_string($lineReaded[2])) {//avoid empty lines csv files
                                if (isset($killsTeam[$lineReaded[2]])) {
                                    $killsTeam[$lineReaded[2]] += (int)$lineReaded[5];
                                    $deathsTeam[$lineReaded[2]] += (int)$lineReaded[6];
                                } else {
                                    $killsTeam[$lineReaded[2]] = (int)$lineReaded[5];
                                    $deathsTeam[$lineReaded[2]] = (int)$lineReaded[6];
                                }
                            }
                        }
                        if ($title == 'VALORANT') {
                            //postion 2 for valorant is team, kills and death 4 and 5 in good format
                            //player 1;nick1;Team A;10;2
                            if (isset($lineReaded[2]) && is_string($lineReaded[2])) {//avoid empty lines csv files
                                if (isset($killsTeam[$lineReaded[2]])) {
                                    $killsTeam[$lineReaded[2]] += (int)$lineReaded[4];
                                    $deathsTeam[$lineReaded[2]] += (int)$lineReaded[5];
                                } else {
                                    $killsTeam[$lineReaded[2]] = (int)$lineReaded[4];
                                    $deathsTeam[$lineReaded[2]] = (int)$lineReaded[5];
                                }

                            }
                        }
                        if (is_array($lineReaded)) {
                            $contents[] = $lineReaded;
                        }


                    } else {
                        $title = $lineReaded[0];
                        $iterationTitle = true;
                    }
                }catch (Exception $e){
                    return $e->getMessage();
                }
            }
            fclose($file_handle);

            //check if matching kills with deaths
            //kills team a match deaths team b
            $integrityKillsDeath = true;

            foreach ($killsTeam as $keyKills => $valueKills) {

                foreach ($deathsTeam as $keyDeaths => $valueDeaths) {
                    if ($keyKills != $keyDeaths) {
                        if ($valueKills !== $valueDeaths) {
                            echo 'compare ' . $title . '----' . $keyDeaths . ' with ' . $keyKills . 'value = ' . $valueKills . '-' . $valueDeaths . '\n';
                            die;

                            $integrityKillsDeath = false;
                        }
                    }
                }
            }
            if ($integrityKillsDeath === true) {
                return $contents;
            } else {
                return ('integrity kills and death failed ');

            }

        } else {
            return ('path to file ' . $path . ' not exist ');
        }
    }


    /**
     * Calculate the total ranking for the game
     * Take the data from cache (previously saved from read csv but this can come from ddbb or other mechanism)
     * @return array with score calculated by each line
     */
    public function calculateTotalRanking()
    {
        $resultsGames = Cache::get('resultsGameLines');
        //data in cache are received in array of games
        foreach ($resultsGames as $keyGame => $game) {

            foreach ($game as $keyPlayer => $playerLineScore) {

                if ($keyGame == 'league') {
                    $newLinePlayerGameData = new dataGamePlayer();
                    if (is_array($playerLineScore)) {

                        $newLinePlayerGameData->setLinePlayerGameData(
                            $keyGame,
                            $playerLineScore[0],
                            $playerLineScore[1],
                            $playerLineScore[2],
                            $playerLineScore[3],
                            $playerLineScore[4],
                            $playerLineScore[5],
                            $playerLineScore[6],
                            $playerLineScore[7],
                            $playerLineScore[8],
                            $playerLineScore[9]);

                        $playersLeague[] = $newLinePlayerGameData->calculateScoreByGameAndPlayer();

                    }
                }
                if ($keyGame == 'valorant') {
                    $newLinePlayerGameData = new dataGamePlayer();
                    if (is_array($playerLineScore)) {
                        //$game, $player, $nick, $team,$winner, $kills, $deaths
                        $newLinePlayerGameData->setLinePlayerGameDataB(
                            $keyGame,
                            $playerLineScore[0],
                            $playerLineScore[1],
                            $playerLineScore[2],
                            $playerLineScore[3],
                            $playerLineScore[4],
                            $playerLineScore[5]
                        );
                        $playersValorant[] = $newLinePlayerGameData->calculateScoreByGameAndPlayer();

                    }
                }

            }
        }

        //apply order
        $playersLeagueNickAndScore = array_column($playersLeague, "score","nick");
        array_multisort($playersLeagueNickAndScore, SORT_DESC, $playersLeagueNickAndScore);

        $playersValorantNickAndScore = array_column($playersValorant, "score","nick");
        array_multisort($playersValorantNickAndScore, SORT_DESC, $playersValorantNickAndScore);

        $totalRanking = array();
        foreach ($playersLeagueNickAndScore as $keyNick => $value) {
            $totalRanking[$keyNick] = $value;
        }
        foreach ($playersValorantNickAndScore as $keyNick => $value) {
            if(isset($totalRanking[$keyNick])){
                $totalRanking[$keyNick]=$totalRanking[$keyNick] + $value;
            }else{
                $totalRanking[$keyNick] = $value;
            }
        }
        asort($totalRanking);

        //encode html message for print
        $htmlReply = "E-Ranking<br>";
        $i=1;
        $position ='position :';
        foreach($totalRanking as $key=>$value){
            $htmlReply .=$position.$i.' Nick :'.$key.' Total points :'.$value.'<br>';
            $i++;
        }
        return json_encode($htmlReply);


    }

}



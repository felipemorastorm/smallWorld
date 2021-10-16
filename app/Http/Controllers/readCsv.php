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
        //here i put the path to the 2 files that i have , i assume what normally the process would push more files
        //then this would be done with  scandisk and read file by file

        $pathCsvLeagueOfLegend = resource_path('csvFiles/league_of_legends.csv');
        $pathCsvValorant = resource_path('csvFiles/valorantGameOk.csv');

        $arrayGames = array('league' => $pathCsvLeagueOfLegend, 'valorant' => $pathCsvValorant);

        $arrayResultGamesByLine = array();
        $displayInformation = '';
        foreach ($arrayGames as $key => $path) {
            $resultsGame = $this->readDataOneGame($path);
            if ($resultsGame !== false) {
                $arrayResultGamesByLine[$key] = $resultsGame;
                $displayInformation .= $key . ' read ok <br>';
            } else {
                $displayInformation .= $key . ' fail <br>';
            }

        }

        //save data in some part with arrayReply Data, save data for trait separately
        //(if so many data maybe we need make a cron or organise how we trait data with queue/cron process)
        //or saving into ddbb results of read lines without validation
        //in this case simply we are going to save results of read line on cache

        Cache::put('resultsGameLines', $arrayResultGamesByLine);

        echo json_encode($displayInformation);
    }


    /**
     * @param $path the path to the csv file that have the score lines
     * @return string the returned string is message callback to return to ui , with ok for each file readed or fail
     *
     */
    public function readDataOneGame($path)
    {
        if (file_exists($path)) {
            $file_handle = fopen($path, 'r');
            $iterationTitle = false;
            while (!feof($file_handle)) {
                $lineReaded = fgetcsv($file_handle, 0, ';');
                //do this for avoid parse data from first header line title of game
                if ($iterationTitle !== false) {
                    $contents[] = $lineReaded;
                } else {
                    $iterationTitle = true;
                }
            }
            fclose($file_handle);
            return (($contents));
        } else {
            return ('path to file ' . $path . ' not exist \n');
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

            if ($keyGame == 'valorant') {
                //need add point in valorant to team winner ????
            }
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
                        $newLinePlayerGameData->setLinePlayerGameDataB(
                            $keyGame,
                            $playerLineScore[0],
                            $playerLineScore[1],
                            $playerLineScore[2],
                            $playerLineScore[3],
                            $playerLineScore[4]);
                        $playersValorant[] = $newLinePlayerGameData->calculateScoreByGameAndPlayer();

                    }
                }

            }
        }

        //order
        $col = array_column($playersLeague, "score");
        array_multisort($col, SORT_DESC, $playersLeague);
        var_dump($playersLeague);

        $col = array_column($playersValorant, "score");
        array_multisort($col, SORT_DESC, $playersValorant);
        var_dump($playersValorant);


    }

}



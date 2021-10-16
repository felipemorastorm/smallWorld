<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/**
 * Class readCsv
 * @package App\Http\Controllers
 */
class readCsv extends Controller
{
    /**
     * Read files on directory (resources/csvFiles)
     * and validate
     */
    public function loadDataFromExcel()
    {
        //here i put the path to the 2 files that i have , i assume what normally the process would push more files
        //then this would be done with  scandisk and read file by file,
        $pathCsvLeagueOfLegend = resource_path('csvFiles/league_of_legends.csv');
        $pathCsvValorant = resource_path('csvFiles/valorant.csv');
        $arrayGames = array('league' => $pathCsvLeagueOfLegend, 'valorant' => $pathCsvValorant);
        $arrayReply = array();
        $displayInformation ='';
        foreach ($arrayGames as $key => $path) {
            $resultsGame = $this->readDataOneGame($path);
            if ($resultsGame !== false) {
                $arrayReply[] = $resultsGame;
                $displayInformation .= $key. ' read ok <br>';
            } else {
                $displayInformation .= $key . ' fail <br>';
            }
        }

        //save data in some part with arrayReply Data
        echo json_encode($displayInformation);
    }

    /**
     * Read files on directory (resources/csvFiles)
     *
     * if file cannot be open return false for this game
     *
     * if file can be open return json data to reply
     *
     * @return false if file cannot be open and contents of lines in array if arrive read file
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
}

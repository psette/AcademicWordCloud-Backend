<?php

namespace App\Http\Controllers;

include_once dirname(__FILE__) . '/../../Model/Paper.php';
include_once dirname(__FILE__) . '/../../Model/ModelSet.php';

include_once dirname(__FILE__) . '/../../Parsers/ACMPaperParser.php';
include_once dirname(__FILE__) . '/../../Parsers/WordParser.php';

include_once dirname(__FILE__) . '/../../../vendor/autoload.php';

use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;
use \ACMPaperParser as ACMPaperParser;
use \ModelSet as ModelSet;

class ACMServer extends BaseController
{
    public static function searchPapers($searchTerm, $searchType, $maximumPaperCount)
    {
        // Get cURL resource
        $ch = curl_init();

        // Set url
        curl_setopt($ch, CURLOPT_URL, 'http://api.acm.org/dl/v1/searchDLNodes?hasFullText=yes&limit=50&offset=0&orderBy=iosTimestamp%2Casc&q=' . $searchTerm);

        // Set method
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

        // Set options
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        // Set headers
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "api_key: 98BE4EB46E5AA6A1016EA327E05B1429856851C10FB54C1602336525B2AC1090"
        ]
        );

        // Send the request & save response to $resp
        $responseText = curl_exec($ch);

        curl_close($ch);

        $json = json_decode($responseText, true);

        $paperParser = new ACMPaperParser();

        $papers = [];

        foreach ($json as $paperJSON) {
            if (count($papers) == $maximumPaperCount) {
                break;
            }

            $paper = $paperParser->parseObject($paperJSON);
            array_push($papers, $paper);
            // if (!is_null($paper)) {
            //     if (strcmp($searchType, "name") == 0) {
            //         foreach ($paper->authors as $author) {
            //             $index = strlen($author) - strlen($searchTerm);

            //             // If $author ends with $searchTerm, it's a match.
            //             if (strripos($author, $searchTerm, 0) === $index) {
            //                 if ($this->parsePaperPDF($paper)) {
            //                     array_push($papers, $paper);
            //                 }
            //                 break;
            //             }
            //         }
            //     } else {
            //         // Assume that if it was returned from our search, it matches well enough.
            //         if ($this->parsePaperPDF($paper)) {
            //             array_push($papers, $paper);
            //         }
            //     }
            // }
        }

        return $papers;
    }        

    public function parsePaperPDF($paper)
    {
        // Get cURL resource
        $ch = curl_init();

        // Set url
        curl_setopt($ch, CURLOPT_URL, $paper->pdf);

        // Set method
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

        // Set options
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        // Set headers
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "api_key: 98BE4EB46E5AA6A1016EA327E05B1429856851C10FB54C1602336525B2AC1090",
            "auth_token: 4BAB6929D28933EEE05010AC5A0A141F",
        ]
        );

        // Send the request & save response to $resp
        $responseText = curl_exec($ch);

        $json = json_decode($responseText, true);
        if (is_null($json) || !array_key_exists("message", $json)) {
            return false;
        }

        $paper->pdf = $json["message"];

        set_time_limit(0);

        //This is the file where we save the    information
        $filepath = dirname(__FILE__) . '/' . $paper->identifier . '.pdf';

        $fp = fopen($filepath, 'w+');

        curl_setopt($ch, CURLOPT_TIMEOUT, 100);

        curl_setopt($ch, CURLOPT_FILE, $fp);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

        // get curl response
        curl_exec($ch);

        curl_close($ch);

        $parser = new \Smalot\PdfParser\Parser();
        $pdf = $parser->parseFile($filepath);

        if ($pdf === "PDF >= 1.5") {
            $text = $paper->abstract;
        } else {
            $text = $pdf->getText();
        }

        $paper->fullWords = $text;
        $papers = new ModelSet();
        $papers->attach($paper);

        $wordParser = new \WordParser();
        $wordParser->papers = $papers;

        $paper->frequentWords = $wordParser->parseWord($paper->fullWords, $paper->title);

        unlink($filepath);

        return true;
    }
}

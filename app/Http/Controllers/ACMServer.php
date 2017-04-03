<?php

namespace App\Http\Controllers;

include_once dirname(__FILE__) . '/../../Model/Paper.php';
include_once dirname(__FILE__) . '/../../Model/Track.php';
include_once dirname(__FILE__) . '/../../Model/Artist.php';
include_once dirname(__FILE__) . '/../../Model/Lyric.php';
include_once dirname(__FILE__) . '/../../Model/ModelSet.php';

include_once dirname(__FILE__) . '/../../Parsers/ACMPaperParser.php';
include_once dirname(__FILE__) . '/../../Parsers/PaperParser.php';
include_once dirname(__FILE__) . '/../../Parsers/TrackParser.php';
include_once dirname(__FILE__) . '/../../Parsers/ArtistParser.php';
include_once dirname(__FILE__) . '/../../Parsers/LyricParser.php';
include_once dirname(__FILE__) . '/../../Parsers/WordParser.php';

include_once dirname(__FILE__) . '/../../../vendor/autoload.php';

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;

use \Paper as Paper;
use \Artist as Artist;
use \Track as Track;
use \Lyric as Lyric;
use \ModelSet as ModelSet;

use \ArtistParser as ArtistParser;
use \TrackParser as TrackParser;
use \ACMPaperParser as ACMPaperParser;
use \LyricParser as LyricParser;
use \PaperParser as PaperParser;
use \WordParser as WordParser;

class ACMServer extends BaseController
{
    public function searchPapers(Request $request, $searchTerm)
    {
        $searchType = $request->input('type');
        $maximumPaperCount = (int)($request->input('count'));

        $artists = [];

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
        "api_key: 98BE4EB46E5AA6A1016EA327E05B1429856851C10FB54C1602336525B2AC1090",
        "auth_token: 4BAB6929D28933EEE05010AC5A0A141F",
        ]
        );

        // Send the request & save response to $resp
        $responseText = curl_exec($ch);

        curl_close($ch);

        if (is_null($responseText))
        {
            $response = json_encode($artists);
            return $response;
        }

        $json = json_decode($responseText, true);

        $paperParser = new ACMPaperParser();
        $paperSerializer = new PaperParser();

        $papers = [];

        foreach ($json as $paperJSON)
        {
            if (count($papers) == $maximumPaperCount)
            {
                break;
            }

            $paper = $paperParser->parseObject($paperJSON);
            if (!is_null($paper))
            {
                if (strcmp($searchType, "name") == 0)
                {
                    foreach ($paper->authors as $author)
                    {
                        $index = strlen($author) - strlen($searchTerm);

                        // If $author ends with $searchTerm, it's a match.
                        if (strripos($author, $searchTerm, 0) === $index)
                        {
                            if ($this->parsePaperPDF($paper))
                            {
                                array_push($papers, $paper);
                            }
                            break;
                        }
                    }
                }
                else
                {
                    // Assume that if it was returned from our search, it matches well enough.
                    if ($this->parsePaperPDF($paper))
                    {
                        array_push($papers, $paper);
                    }
                }
            }
        }

        $artist = new Artist();
        $artist->name = $searchTerm;
        $artist->identifier = $artist->name;
        $artist->imageURL = "https://cdn1.iconfinder.com/data/icons/appicns/513/appicns_iTunes.png";

        // Determine frequent words from the papers.
        $artist->frequentLyrics = Paper::frequentWordsFromPapers($papers);

        array_push($artists, $artist);

        // Encode Artist objects to JSON to send to client.
        $artistParser = new ArtistParser();
        $serialized = array_map([$artistParser, "serializeObject"], $artists);

        $serializedPapers = array_map([$paperSerializer, "serializeObject"], $papers);

        $serialized[0]["tracks"] = $serializedPapers;

        $encoded = json_encode($serialized);

        // Allow cross-origin-requests so javascript can make requests.
        return response($encoded, 200)
                  ->header('Content-Type', 'application/json')
                  ->header('Access-Control-Allow-Origin', '*');
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
        if (is_null($json) || !array_key_exists("message", $json))
        {
            return false;
        }

        $paper->pdf = $json["message"];

        set_time_limit(0);

        //This is the file where we save the    information
        $filepath = dirname(__FILE__) . '/' . $paper->identifier . '.pdf';

        $fp = fopen ($filepath, 'w+');

        curl_setopt($ch, CURLOPT_TIMEOUT, 100);

        curl_setopt($ch, CURLOPT_FILE, $fp);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

        // get curl response
        curl_exec($ch);

        curl_close($ch);
        fclose($fp);

        $parser = new \Smalot\PdfParser\Parser();
        try{
            $pdf   = $parser->parseFile($filepath);
            $text = $pdf->getText();
        } catch(Exeception $e){
            $text = $paper->abstract;
        }
        $paper->fullWords = $text;
        $papers = new ModelSet();
        $papers->attach($paper);

        $wordParser = new WordParser();
        $wordParser->papers = $papers;

        $paper->frequentWords = $wordParser->parseObject($paper->fullWords);

        unlink($filepath);

        return true;
    }
}
?>

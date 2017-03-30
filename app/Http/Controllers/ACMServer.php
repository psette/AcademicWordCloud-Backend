<?php

namespace App\Http\Controllers;

include_once dirname(__FILE__) . '/../../Model/Paper.php';
include_once dirname(__FILE__) . '/../../Model/Track.php';
include_once dirname(__FILE__) . '/../../Model/Artist.php';
include_once dirname(__FILE__) . '/../../Model/Lyric.php';

include_once dirname(__FILE__) . '/../../Parsers/ACMPaperParser.php';
include_once dirname(__FILE__) . '/../../Parsers/PaperParser.php';
include_once dirname(__FILE__) . '/../../Parsers/TrackParser.php';
include_once dirname(__FILE__) . '/../../Parsers/ArtistParser.php';
include_once dirname(__FILE__) . '/../../Parsers/LyricParser.php';

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;

use \Paper as Paper;
use \Artist as Artist;
use \Track as Track;
use \Lyric as Lyric;

use \ArtistParser as ArtistParser;
use \TrackParser as TrackParser;
use \ACMPaperParser as ACMPaperParser;
use \LyricParser as LyricParser;
use \PaperParser as PaperParser;

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
        curl_setopt($ch, CURLOPT_URL, 'http://api.acm.org/dl/v1/searchDLNodes?hasFullText=yes&limit=100&offset=0&orderBy=iosTimestamp%2Casc&q=' . $searchTerm);

        // Set method
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

        // Set options
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        // Set headers
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "api_key: 98BE4EB46E5AA6A1016EA327E05B1429856851C10FB54C1602336525B2AC1090",
        "Cookie: JSESSIONID=FC069D9261B990B2B66BAB26F8392AEE",
        ]
        );

        // Send the request & save response to $resp
        $responseText = curl_exec($ch);

        if (is_null($responseText))
        {
            curl_close($ch);

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
                            array_push($papers, $paper);
                            break;
                        }
                    }
                }
                else 
                {
                    // Assume that if it was returned from our search, it matches well enough.
                    array_push($papers, $paper);
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
}
?>
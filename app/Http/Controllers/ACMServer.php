<?php

namespace App\Http\Controllers;

include_once dirname(__FILE__) . '/../../Model/Track.php';
include_once dirname(__FILE__) . '/../../Model/Artist.php';
include_once dirname(__FILE__) . '/../../Model/Lyric.php';

include_once dirname(__FILE__) . '/../../Parsers/ACMPaperParser.php';
include_once dirname(__FILE__) . '/../../Parsers/TrackParser.php';
include_once dirname(__FILE__) . '/../../Parsers/ArtistParser.php';
include_once dirname(__FILE__) . '/../../Parsers/LyricParser.php';

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;

use \Artist as Artist;
use \Track as Track;
use \Lyric as Lyric;

use \ArtistParser as ArtistParser;
use \TrackParser as TrackParser;
use \ACMPaperParser as ACMPaperParser;
use \LyricParser as LyricParser;

class ACMServer extends BaseController
{
    public function searchPapers(Request $request, $searchTerm)
    {
        $artists = [];

        $artist = new Artist();
        $artist->name = $searchTerm;
        $artist->identifier = $artist->name;
        $artist->imageURL = "https://cdn1.iconfinder.com/data/icons/appicns/513/appicns_iTunes.png";

        // Get cURL resource
        $ch = curl_init();

        // Set url
        curl_setopt($ch, CURLOPT_URL, 'http://api.acm.org/dl/v1/searchDLNodes?hasFullText=yes&limit=20&offset=0&orderBy=iosTimestamp%2Casc&q=' . $searchTerm);

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

        $artistParser = new ArtistParser();

        $trackParser = new ACMPaperParser();
        $trackParser->artist = $artist;

        foreach ($json as $trackJSON)
        {
            $track = $trackParser->parseObject($trackJSON);
            if (!is_null($track))
            {
                $artist->tracks->attach($track);
            }
        }

        // Determine frequent lyrics for an Artist from the tracks.
        $artist->frequentLyrics = Track::frequentLyricsFromTracks($artist->tracks);
        
        array_push($artists, $artist);
        
        // Encode Artist objects to JSON to send to client.
        $serialized = array_map([$artistParser, "serializeObject"], $artists);
        $encoded = json_encode($serialized);

        // Allow cross-origin-requests so javascript can make requests.
        return response($encoded, 200)
                  ->header('Content-Type', 'application/json')
                  ->header('Access-Control-Allow-Origin', '*');
    }

    
}
?>
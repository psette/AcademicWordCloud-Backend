<?php

namespace App\Http\Controllers;

include_once dirname(__FILE__) . '/../../Model/Track.php';
include_once dirname(__FILE__) . '/../../Model/Artist.php';
include_once dirname(__FILE__) . '/../../Model/Lyric.php';

include_once dirname(__FILE__) . '/../../Parsers/TrackParser.php';
include_once dirname(__FILE__) . '/../../Parsers/ArtistParser.php';
include_once dirname(__FILE__) . '/../../Parsers/LyricParser.php';

include_once dirname(__FILE__) . '/Controller.php';

use Illuminate\Http\Request;

use \Artist as Artist;
use \Track as Track;
use \Lyric as Lyric;

use \ArtistParser as ArtistParser;
use \TrackParser as TrackParser;
use \LyricParser as LyricParser;

/**
 * The class handling the Server logic.
 */
class Server extends Controller
{

  /*
     * Search for artists matching provided text.
     *
     * @param Artist $artist to search for
     * @return String of lyrics.wikia.com artist page
     *         Null if page cannot be rendered
     *
     */
    public function getArtistPageString($artist)
    {
        // get the contents of the wikia search
        $location = "http://lyrics.wikia.com/wikia.php?controller=LyricsApi&method=searchArtist&query=" . urlencode($artist);
        $file = @file_get_contents(html_entity_decode($location));
        return $file;
    }

    /*
     * Search for artists matching provided text.
     *
     * @param Request $request
     * @param string $artist
     *
     * @return JSON-encoded Artists array.
     *
     */
    public function searchArtists(Request $request, $artist)
    {
        $artists = [];

        $file = $this->getArtistPageString($artist);

        if ($file == FALSE)
        {
            return $artists;
        }

        $json = json_decode($file, true);

        $artistParser = new ArtistParser();
        $trackParser = new TrackParser();
        $lyricParser = new LyricParser();

        // If results key does not exist, an error occured so return empty array.
        if (!array_key_exists("result", $json))
        {
            $response = json_encode($artists);
            return $response;
        }

        $artistCount = 0;

        foreach ($json["result"] as $artistJSON)
        {
            $artist = $artistParser->parseObject($artistJSON);

            if (array_key_exists("songs", $artistJSON))
            {
                // Ensure no more than 3 artists are returned
                $artistCount = $artistCount + 1;
                if ($artistCount > 3)
                {
                    break;
                }

                $array = $artistJSON["songs"];
                foreach ($artistJSON["songs"] as $trackJSON)
                {
                    // Parse limited trackJSON to obtain url value.
                    // The full track JSON will be fetched + parsed in fetchTrack() (including lyrics, which aren't present here)
                    $tempTrack = $trackParser->parseObject($trackJSON);

                    if (!is_null($tempTrack))
                    {
                        $track = $this->fetchTrack($tempTrack->url, $artist);

                        if (!is_null($track))
                        {
                            $artist->tracks->attach($track);
                        }
                    }
                }
                
                // Determine frequent lyrics for an Artist from the tracks.
                $artist->frequentLyrics = Track::frequentLyricsFromTracks($artist->tracks);

                array_push($artists, $artist);
            }
        }

        // Encode Artist objects to JSON to send to client.
        $serialized = array_map([$artistParser, "serializeObject"], $artists);
        $encoded = json_encode($serialized);

        // Allow cross-origin-requests so javascript can make requests.
        return response($encoded, 200)
                  ->header('Content-Type', 'application/json')
                  ->header('Access-Control-Allow-Origin', '*');
    }

    /*
     * Fetch track metadata for a given track URL.
     *
     * @param string $url
     * @param Artist $artist
     *
     * @return Track.
     *
     */
    private function fetchTrack($url, $artist)
    {
        $file = @file_get_contents($url);
        if ($file == FALSE)
        {
            return null;
        }

        $json = json_decode($file, true);

        // If no result key, there was an error so return null.
        if (!array_key_exists("result", $json))
        {
            return null;
        }

        $trackJSON = $json["result"];

        $trackParser = new TrackParser();
        $trackParser->artist = $artist;

        $track = $trackParser->parseObject($trackJSON);

        return $track;
    }
}

?>

<?php

namespace App\Http\Controllers;
include_once dirname(__FILE__) . '/../../Model/Track.php';
include_once dirname(__FILE__) . '/../../Model/Artist.php';
include_once dirname(__FILE__) . '/../../Model/Lyric.php';

include_once dirname(__FILE__) . '/../../Parsers/TrackParser.php';
include_once dirname(__FILE__) . '/../../Parsers/ArtistParser.php';
include_once dirname(__FILE__) . '/../../Parsers/LyricParser.php';

include_once dirname(__FILE__) . '/Controller.php';

include_once dirname(__FILE__) . '/../../Jobs/FetchLyrics.php';
include_once dirname(__FILE__) . '/../../Jobs/FetchTrack.php';


use App\Jobs\FetchLyrics;
use App\Jobs\FetchTrack;
use Illuminate\Http\Request;

use \Artist as Artist;
use \Track as Track;
use \Lyric as Lyric;

use \ArtistParser as ArtistParser;
use \TrackParser as TrackParser;
use \LyricParser as LyricParser;

class SearchController extends Controller
{
    /*
     *  Search artists
     *
     * @param Request $request
     * @param string $artist
     *
     * @return Artist
     *
     */
     public function searchArtists(Request $request, $artist)
    {
        $artists = [];
        $location = "http://lyrics.wikia.com/wikia.php?controller=LyricsApi&method=searchArtist&query=" . urlencode($artist);

        $file = @file_get_contents(html_entity_decode($location));
        if ($file == FALSE)
        {
            return $artists;
        }
        $json = json_decode($file, true);
        $artistParser = new ArtistParser();
        $trackParser = new TrackParser();
        $lyricParser = new LyricParser();
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
                        $track = $this->fetchTrack($tempTrack, $artist);
                        if (!is_null($track))
                        {
                            $artist->tracks->attach($track);
                        }
                    }
                }
                array_push($artists, $artist);
            }
        }
        $serialized = array_map([$artistParser, "serializeObject"], $artists);
        $encoded = json_encode($serialized);
        return $encoded;
    }

    /*
     * Create a job for fetchig the tracks
     * 
     */
    private function fetchTrack($trackURL, $trackArtist)
    {
        $this->dispatch(new FetchTrack($trackURL, $trackArtist));
    }
}
?>

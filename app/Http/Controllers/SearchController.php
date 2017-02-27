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

class SearchController extends Controller
{
    /*
     *  Search artist
     *
     * @param Request $request
     * @param string $artist
     *
     * @return Artist
     *
     */
    // public  function fetchTracks($artist ,$artistsArray, $callback = 'fetchTracksCallBack')
    // {

    //     $tracks[] =  new \Track();
    //     $parser = new \TrackParser();

    //     foreach( $artistsArray['songs'] as $trackItem ) {
    //         if(!array_key_exists("url", $trackItem)){
    //             echo "URL Does not exsit for " . $trackItem['name'];
    //             continue;
    //         }
    //         $track = $parser->parseObject($trackItem);
    //         $track->artist = $artist;
    //         array_push($tracks, $track);
    //         $this->fetchLyrics($track);
    //     }

    //     return;
    // }

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

    private function fetchTrack($url, $artist)
    {
        $url = "http://lyrics.wikia.com/wikia.php?controller=LyricsApi&method=getSong&artist=Kanye+West&song=Drop+Dead+Gorgeous";

        $file = @file_get_contents(html_entity_decode($url));
        if ($file == FALSE)
        {
            return null;
        }

        $json = json_decode($file, true);

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



    // function fetchTracksCallBack($artist , $tracks)
    // {

    //     $lyricsDictionary = Array();
    //     $artist->tracks = $tracks;

    //     foreach($tracks as $track){

    //         if( !is_null($tracks->$lyrics) ){

    //         }

    //     }

    // }
    //TODO FINISH LYRIC FETCHING
    // function fetchLyrics($track, $callback = 'fetchLyricsCallBack')
    // {
    //     $location = $track->url;
    //     $file = @file_get_contents(html_entity_decode($location));

    //     if($file == FALSE ){
    //         echo " File does not exsist for $track->name \n.";
    //         return;
    //     }

    //     $json = json_decode($file, true);


    //     $raw_lyrics  = $json["result"]["lyrics"];
    //     //HERE ARE THE LYRICS,
    //     return $json;
    // }

    // function fetchLyricsCallBack($track, $lyrics)
    // {
    //     $track->lyrics = $lyrics;
    // }

}

?>

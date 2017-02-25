<?php

namespace App\Http\Controllers;

include_once dirname(__FILE__) . '/../../Model/Track.php';
include_once dirname(__FILE__) . '/../../Model/Artist.php';

include_once dirname(__FILE__) . '/../../Parsers/TrackParser.php';
include_once dirname(__FILE__) . '/../../Parsers/ArtistParser.php';

include_once dirname(__FILE__) . '/Controller.php';

use Illuminate\Http\Request;

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
    public  function fetchTracks($artist ,$artistsArray, $callback = 'fetchTracksCallBack')
    {

        $tracks[] =  new \Track();
        $parser = new \TrackParser();

        foreach( $artistsArray['songs'] as $trackItem ) {
            if(!array_key_exists("url", $trackItem)){
                echo "URL Does not exsit for " . $trackItem['name'];
                continue;
            }
            $track = $parser->parseObject($trackItem);
            $track->artist = $artist;
            array_push($tracks, $track);
            $this->fetchLyrics($track);
        }

        return;
    }

    public function searchArtists(Request $request, $artist)
    {
        $location = "http://lyrics.wikia.com/wikia.php?controller=LyricsApi&method=searchArtist&query=" . urlencode($artist);
        $file = file_get_contents($location);
        $json = json_decode($file, true);
        $artists[] =  new \Artist();
        $parser = new \ArtistParser();

        if( !array_key_exists("result", $json)){
            echo "No Artist found under the name $artist \n";
            return;
        }

        foreach($json['result'] as $artistsArray) {
            $artist = $parser->parseObject($artistsArray);
            array_push($artists, $artist);

            if( array_key_exists( "songs",   $artistsArray) ){
                $this->fetchTracks($artist, $artistsArray);
            }

        }

        return;
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
    function fetchLyrics($track, $callback = 'fetchLyricsCallBack')
    {
        $location = $track->url;
        $file = @file_get_contents(html_entity_decode($location));

        if($file == FALSE ){
            echo " File does not exsist for $track->name \n.";
            return;
        }

        $json = json_decode($file, true);


        $raw_lyrics  = $json["result"]["lyrics"];
        //HERE ARE THE LYRICS,
        return $json;
    }

    function fetchLyricsCallBack($track, $lyrics)
    {
        $track->lyrics = $lyrics;
    }

}

?>

<?php

namespace App\Http\Controllers;

use App\User;

class SearchController extends Controller
{
    /*
     * @param: Artist name to search
     * @return 
     *
     */
    function searchArtists($artist = "Kanye") 
    {

        $location = "http://api.musixmatch.com/ws/1.1/artist.search?apikey=3af1548222b3186a1aa15607251d8fea&page_size=5&q_artist=" . urlencode($artist);
        $file = file_get_contents($location);
        $json = json_decode($file, true);
        $artists[] =  new Artist();
        $parser = new ArtistParser();

            foreach($json['message']['body']['artist_list'] as $artistInfo) {
                $artist = $parser->parsedObject($artistInfo['artist']);
                array_push($artists, $artist);
                $tracks = fetchTracks($artist);
            }
        
        return;
    }


    function fetchTracks($artist = "Kanye", $callback = 'fetchTracksCallBack') 
    {
        $location = "http://api.musixmatch.com/ws/1.1/track.search?apikey=3af1548222b3186a1aa15607251d8fea&page_size=100&f_artist_id=" . urlencode($artist->identifier);
        $file = file_get_contents($location);
        $json = json_decode($file, true);
        $tracks[] =  new Track();
        $parser = new TrackParser();

        foreach($json['message']['body']['track_list'] as $trackInfo) {
            $track = $parser->parsedObject($trackInfo['track']);
            $track->artist = $artist;
            array_push($tracks, $track);
            fetchLyrics($track);
        }

        $callback($artist, $tracks);
        return $tracks;
    }


    function fetchTracksCallBack($artist , $tracks) 
    { 

        $lyricsDictionary = Array();
        $artist->tracks = $tracks;

        foreach($tracks as $track){

            if( !is_null($tracks->$lyrics) ){

            }

        }

    } 

    function fetchLyrics($track, $callback = 'fetchLyricsCallBack')
    {
        $callback($lyrics);
        return $lyrics;
    }

    function fetchLyricsCallBack($track, $lyrics)
    {
        $track->lyrics = $lyrics;
    }

}

?>
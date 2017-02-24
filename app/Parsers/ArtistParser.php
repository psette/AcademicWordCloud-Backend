<?php

require('Parser.php');
include_once dirname(__FILE__) . '/../Model/Artist.php';

class ArtistParser implements Parser
{
    /**
     * Parses the JSON and returns an Artist.
     *
     * @param array $json The JSON representation of an Artist.
     *
     * @return Artist Returns an Artist populated with data from $json.
     */
    function parseObject($json)
    {
        $artist = new Artist();
        $artist->name = $json["artist_name"];
        $artist->identifier = $json["artist_id"];
        return $artist;
    }

    /**
     * Serializes an Artist to JSON.
     *
     * @param artist $artist The Artist to be serialized.
     *
     * @return array Returns the JSON representation of the Artist.
     */
    function serializeObject($artist)
    {
        // declare a TrackParser and LyricsParser for storing information about the artist and lyrics
        $trackParser = new TrackParser();
        $lyricsParser = new LyricsParser();
        $tracks = Array();

        // loop through the tracks for the artist and add them to an array
        foreach ($artist->tracks as $key)
        {
            $track = $artist->tracks[$key];
            $json = $trackParser.serializeObject($track);
            array_push($tracks, $track);
        }

        // define a look-up table of relevant artist info
        $json = array(
             "name" => $artist->name,
             "identifier" => $artist->identifier,
             "tracks" => $tracks,
             "frequentLyrics" => $lyricsParser.serializeObject($artist->frequentLyrics)
        );
        return $json;
    }
}
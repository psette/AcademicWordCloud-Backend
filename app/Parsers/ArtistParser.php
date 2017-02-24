<?php

include('Parser.php');
include('Artist.php');

class ArtistParser implements JSONParser
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
        $trackParser = new TrackParser();
        $lyricsParser = new LyricsParser();

        $tracks = Array();

        foreach ($artist->tracks as $key)
        {
            $track = $artist->tracks[$key];
            $json = $trackParser.serializeObject($track);
            array_push($tracks, $track);
        }

        $json = array(
             "name" => $artist->name,
             "identifier" => $artist->identifier,
             "tracks" => $tracks,
             "frequentLyrics" => $lyricsParser.serializeObject($artist->frequentLyrics),
        );
        return $json;
    }
}
<?php

require('Parser.php');
require('../Model/Track.php');

class TrackParser implements Parser
{
    /**
     * Parses the JSON and returns a Track.
     *
     * @param string $json The JSON representation of a Track.
     *
     * @return Track Returns a Track populated with data from $json.
     */
    function parseObject($json)
    {
        $track = new Track();
        $track->name = $json["track_name"];
        $track->identifier = $json["track_id"];
        return $track;
    }

    /**
     * Serializes a Track to JSON.
     *
     * @param Track $track The Track to be serialized.
     *
     * @return array Returns the JSON representation of the Track.
     */
    function serializeObject($track)
    {
        $lyricsParser = new LyricsParser();

        $json = array(
             "name" => $track->name,
             "identifier" => $track->identifier,
             "frequentLyrics" => $lyricsParser.serializeObject($track->frequentLyrics),
        );
        return $json;
    }
}
?>
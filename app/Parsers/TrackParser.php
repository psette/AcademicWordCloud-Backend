<?php

include_once dirname(__FILE__) . '/Parser.php';
include_once dirname(__FILE__) . '/../Model/Track.php';

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
        $track->name = $json["name"];
        $track->url = $json["url"];
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

        // store name, identifier, and the frequent lyrics
        $json = array(
             "name" => $track->name,
             "url" => $track->url,
             "frequentLyrics" => $lyricsParser.serializeObject($track->frequentLyrics),
        );
        return $json;
    }
}
?>

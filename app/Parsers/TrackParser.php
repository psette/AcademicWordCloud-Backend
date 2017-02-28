<?php

include_once dirname(__FILE__) . '/Parser.php';
include_once dirname(__FILE__) . '/LyricParser.php';

include_once dirname(__FILE__) . '/../Model/Track.php';

class TrackParser implements Parser
{
    var $artist;

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

        if (array_key_exists("url", $json))
        {
            $track->url = $json["url"];
            $track->identifier = $track->url;
        }
        else
        {
            $track->url = "";
            $track->identifier = $track->name;
        }

        $track->artist = $this->artist;
        
        if (array_key_exists("lyrics", $json))
        {
            $track->lyrics = $json["lyrics"];

            $tracks = new ModelSet();
            $tracks->attach($track);

            $lyricParser = new LyricParser();
            $lyricParser->tracks = $tracks;

            $track->frequentLyrics = $lyricParser->parseObject($track->lyrics);
        }
        else
        {
            $track->frequentLyrics = [];
        }

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
        $tracks = new ModelSet();
        $tracks->attach($track);

        $lyricParser = new LyricParser();
        $lyricParser->tracks = $tracks;

        // store name, identifier, and the frequent lyrics
        $json = [
             "name" => $track->name,
             "identifier" => $track->identifier,
             "lyrics" => $track->lyrics,
             "frequentLyrics" => array_map([$lyricParser, "serializeObject"], $track->frequentLyrics),
        ];
        return $json;
    }
}
?>

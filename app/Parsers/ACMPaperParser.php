<?php
include_once dirname(__FILE__) . '/Parser.php';
include_once dirname(__FILE__) . '/../Model/Track.php';

class ACMPaperParser implements Parser
{
    var $artist;

    public function parseObject($json)
    {
        $track = new Track();

        $track->name = $json["title"];
        if (array_key_exists("subtitle", $json))
        {
            $track->name .= ": " . $json["subtitle"];
        }

        $track->identifier = $json["objectId"];

        if (array_key_exists("abstract", $json))
        {
            $track->lyrics = $json["abstract"];

            $tracks = new ModelSet();
            $tracks->attach($track);

            $lyricParser = new LyricParser();
            $lyricParser->tracks = $tracks;

            $track->frequentLyrics = $lyricParser->parseObject($json["abstract"]);
        }
        else
        {
            $track->frequentLyrics = [];
        }

        $track->artist = $this->artist;

        return $track;
    }

    function serializeObject($track)
    {

    }
}

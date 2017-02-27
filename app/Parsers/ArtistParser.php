<?php
include_once dirname(__FILE__) . '/Parser.php';
include_once dirname(__FILE__) . '/TrackParser.php';
include_once dirname(__FILE__) . '/LyricParser.php';

include_once dirname(__FILE__) . '/../Model/Artist.php';
include_once dirname(__FILE__) . '/../Model/ModelSet.php';

class ArtistParser implements Parser
{
    var $tracks;

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
        $artist->name = $json["name"];
        $artist->identifier = $artist->name;
        $artist->imageURL = $json["small_image"];

        if (!is_null($lyric))
        {
            $lyrics = [];

            foreach ($this->tracks as $key)
            {
                $track = $this->tracks[$key];

                foreach ($track->frequentLyrics as $frequentLyric) 
                {
                    $lyric = $lyrics[$frequentLyric->stringValue];
                    if (is_null($lyric))
                    {
                        $lyric = new Lyric();
                        $lyric->stringValue = $frequentLyric->stringValue;
                        $lyric->identifier = $frequentLyric->stringValue;
                    }

                    $lyric->frequency = $lyric->frequency + 1;
                    $lyric->tracks->attach($track);
                    
                    $lyrics[$frequentLyric->stringValue] = $lyric;
                }
            }

            usort($lyrics, ["Lyric", "compareByFrequency"]);

            $artist->frequentLyrics = $lyrics;
        }

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
        $trackParser->artist = $artist;

        $lyricsParser = new LyricsParser();
        $lyricsParser->tracks = $artist->tracks;

        $tracksArray = [];

        // No "map" function for SplObjectStorage, so must map values manually
        foreach ($artist->tracks as $key)
        {
            $track = $artist->tracks[$key];
            $json = $trackParser.serializeObject($track);

            $tracksArray[] = $json;
        }

        // define a look-up table of relevant artist info
        $json = [
             "name" => $artist->name,
             "identifier" => $artist->identifier,
             "imageURL" => $artist->imageURL,
             "tracks" => $tracksArray,
             "frequentLyrics" => array_map([$lyricParser, "serializeObject"], $artist->frequentLyrics),
        ];

        return $json;
    }
}

?>

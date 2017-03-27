<?php
include_once dirname(__FILE__) . '/Parser.php';
include_once dirname(__FILE__) . '/PaperParser.php';
include_once dirname(__FILE__) . '/WordParser.php';

include_once dirname(__FILE__) . '/../Model/Author.php';
include_once dirname(__FILE__) . '/../Model/ModelSet.php';

/**
 * Parser to parse Author objects.
 */
class AuthorParser implements Parser
{
    /**
    * Tracks that were written by the Authors to be parsed.
    *
    * @var ModelSet
    */
    var $papers;

    /**
     * Parses the JSON and returns an Author.
     *
     * @param array The key-value store of the representation of an Author.
     *
     * @return Author Returns an Author populated with data from $json.
     */
    function parseObject($json)
    {
        $Author = new Author();
        $Author->name = $json["name"];
        $Author->identifier = $Author->name;

        if (array_key_exists("small_image", $json))
        {
            $Author->imageURL = $json["small_image"];
        }
        else
        {
            $Author->imageURL = "https://cdn1.iconfinder.com/data/icons/appicns/513/appicns_iTunes.png";
        }

        $Author->frequentLyrics = Paper::frequentWordsFromPapers($this->papers);
        return $Author;
    }

    /**
     * Serializes an Author to JSON.
     *
     * @param Author $Author The Author to be serialized.
     *
     * @return array Returns the JSON representation of the Author.
     */
    function serializeObject($Author)
    {
        // declare a TrackParser and LyricsParser for storing information about the Author and words
        $paperParser = new PaperParser();
        $paperParser->Author = $Author;

        $paperParser = new LyricParser();
        $paperParser->tracks = $Author->tracks;

        $paperArray = [];

        if (!is_null($Author->tracks))
        {
            // No "map" function for SplObjectStorage, so must map values manually
            foreach ($Author->papers as $paper)
            {
                $json = $paperParser->serializeObject($paper);
                $paperArray[] = $json;
            }
        }

        // define a look-up table of relevant Author info
        $json = [
             "name" => $Author->name,
             "identifier" => $Author->identifier,
             "imageURL" => $Author->imageURL,
             "tracks" => $paperArray,
             "frequentLyrics" => array_map([$paperParser, "serializeObject"], $Author->frequentWords ?: []),
        ];

        return $json;
    }
}

?>

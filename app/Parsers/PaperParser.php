<?php

include_once dirname(__FILE__) . '/Parser.php';
include_once dirname(__FILE__) . '/WordParser.php';

include_once dirname(__FILE__) . '/../Model/Paper.php';

/**
 * Parser to parse Paper objects.
 */
class PaperParser implements Parser
{
    /**
    * The Artist that composed the Papers to be parsed.
    *
    * @var Artist
    */
    var $author;

    /**
     * Parses the JSON and returns a Paper.
     *
     * @param string $json The JSON representation of a Paper.
     *
     * @return Paper Returns a Paper populated with data from $json.
     */
    function parseObject($json)
    {
        $paper = new Paper();
        $paper->name = $json["name"];

        if (array_key_exists("url", $json))
        {
            $paper->url = $json["url"];
            $paper->identifier = $paper->url;
        }
        else
        {
            $paper->url = "";
            $paper->identifier = $paper->name;
        }

        $paper->author = $this->author;

        if (array_key_exists("lyrics", $json))
        {
            $paper->lyrics = $json["lyrics"];
            $papers = new ModelSet();
            $papers->attach($paper);

            $wordParser = new WordParser();
            $lyricParser->Papers = $Papers;

            $paper->frequentWords = $lyricParser->parseObject($json["lyrics"]);
        }
        else
        {
            $paper->frequentWords = [];
        }

        return $paper;
    }

    /**
     * Serializes a Paper to JSON.
     *
     * @param Paper $paper The Paper to be serialized.
     *
     * @return array Returns the JSON representation of the Paper.
     */
    function serializeObject($paper)
    {
        $papers = new ModelSet();
        $papers->attach($paper);

        $lyricParser = new LyricParser();
        $lyricParser->papers = $papers;

        // store name, identifier, and the frequent lyrics
        $json = [
             "name" => $paper->name,
             "identifier" => $paper->identifier,
             "lyrics" => $paper->words,
             "frequentLyrics" => array_map([$wordParser, "serializeObject"], $paper->frequentWords),
        ];
        return $json;
    }
}
?>

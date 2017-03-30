<?php

include_once dirname(__FILE__) . '/Parser.php';
include_once dirname(__FILE__) . '/WordParser.php';

include_once dirname(__FILE__) . '/../Model/Paper.php';

/**
 * Parser to parse Paper objects.
 */
class PaperParser implements Parser
{
    function parseObject($json)
    {
        // Do not use
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

        $wordParser = new WordParser();
        $wordParser->papers = $papers;

        // store name, identifier, and the frequent lyrics
        $json = [
             "name" => $paper->title,
             "identifier" => $paper->identifier,
             "lyrics" => $paper->fullWords,
             "frequentLyrics" => array_map([$wordParser, "serializeObject"], $paper->frequentWords),
        ];
        return $json;
    }
}
?>

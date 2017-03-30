<?php
include_once dirname(__FILE__) . '/Parser.php';
include_once dirname(__FILE__) . '/WordParser.php';
include_once dirname(__FILE__) . '/../Model/Paper.php';

class ACMPaperParser implements Parser
{    
    public function parseObject($json)
    {
        $paper = new Paper();

        $paper->title = $json["title"];
        if (array_key_exists("subtitle", $json))
        {
            $paper->title .= ": " . $json["subtitle"];
        }

        $paper->identifier = $json["objectId"];


        if (array_key_exists("abstract", $json))
        {
            $paper->fullWords = $json["abstract"];

            $papers = new ModelSet();
            $papers->attach($paper);

            $wordParser = new WordParser();
            $wordParser->papers = $papers;

            $paper->frequentWords = $wordParser->parseObject($json["abstract"]);
        }
        else
        {
            $paper->frequentWords = [];
        }

        return $paper;
    }

    function serializeObject($track)
    {
        // Do not use
    }
}
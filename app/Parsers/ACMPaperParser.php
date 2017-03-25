<?php
include_once dirname(__FILE__) . '/Parser.php';
include_once dirname(__FILE__) . '/WordParser.php';
include_once dirname(__FILE__) . '/PaperParser.php';
include_once dirname(__FILE__) . '/../Model/Paper.php';

class ACMPaperParser implements Parser
{    
    public function parseObject($json)
    {
        $paper = new Paper();

        return $paper;
    }

    function serializeObject($paper)
    {
    }
}
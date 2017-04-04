<?php
include_once dirname(__FILE__) . '/Parser.php';
include_once dirname(__FILE__) . '/WordParser.php';
include_once dirname(__FILE__) . '/../Model/Paper.php';

class ACMPaperParser implements Parser
{
    public function parseObject($json)
    {
        $paper = new Paper();
        $paper->abstract = $json["abstract"];
        $paper->title = $json["title"];
        if (array_key_exists("subtitle", $json))
        {
            $paper->title .= ": " . $json["subtitle"];
        }

        $paper->identifier = $json["objectId"];

        foreach ($json["persons"] as $person)
        {
            $name = $person["displayName"];
            array_push($paper->authors, $name);
        }

        foreach ($json["tags"] as $tag)
        {
            $keyword = $tag["tag"];
            array_push($paper->keywords, $keyword);
        }

        if (array_key_exists("attribs", $json))
        {
            $array = $json["attribs"];

            foreach ($array as $attributes)
            {
                if (strcmp($attributes["type"], "fulltext") == 0 && strcmp($attributes["format"], "pdf") == 0)
                {
                    if (array_key_exists("source", $attributes))
                    {
                        $paper->pdf = "http://api.acm.org/dl/v1/download?type=fulltext&url=" . urlencode($attributes["source"]);
                        break;
                    }
                }
            }
        }

        if (is_null($paper->pdf))
        {
            return null;
        }

        return $paper;
    }

    function serializeObject($track)
    {
        // Do not use
    }
}

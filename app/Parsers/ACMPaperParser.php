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
        $paper->bibtex = $this->parseBibtextLinkFromDownload($paper->identifier);

        if (array_key_exists("abstract", $json))
        {
            $paper->abstract = $json["abstract"];
        }

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
                        $paper->download = $paper->pdf;
                        break;
                    }
                }
            }
        }

        if (array_key_exists("parentId", $json)) {
            $paper->conferenceID = $json["parentId"];
        }

        if (array_key_exists("parentTitle", $json)) {
            $paper->conference = $json["parentTitle"];
        }

        if (is_null($paper->pdf) || is_null($paper->title) || is_null($paper->identifier) || is_null($paper->download) || is_null($paper->bibtex) || is_null($paper->abstract))
        {
            return null;
        }

        return $paper;
    }

    /**
     * Returns the link to the citation by extracting the article number.
     *
     * @param Arnumber $arnumber The download link of the paper.
     *
     * @return string Returns the string representation of bibtex link.
     */
    public function parseBibtextLinkFromDownload($id)
    {

        $link = "http://dl.acm.org/citation.cfm?id=" . $id . "&preflayout=flat";

        return $link;
    }

    /**
     * Serializes an paper to JSON.
     *
     * @param Paper $Paper The Paper to be serialized.
     *
     * @return array Returns the JSON representation of the Paper.
     */
    public function serializeObject($paper)
    {
        $papers = new ModelSet();
        $papers->attach($paper);

        $wordParser = new WordParser();
        $wordParser->papers = $papers;

        // define a look-up table of relevant Paper info
        $json = [
            "title" => $paper->title,
            "bibtex" => $paper->bibtex,
            "download" => $paper->download,
            "pdf" => $paper->pdf,
            "pubYear" => $paper->pubYear,
            "fullWords" => $paper->fullWords,
            "frequentWords" => array_map([$wordParser, "serializeObject"], $paper->frequentWords),
            "authors" => $paper->authors,
            "keywords" => $paper->keywords,
            "abstract" => $paper->abstract,
        ];

        return $json;
    }
}

<?php
include_once dirname(__FILE__) . '/Parser.php';
include_once dirname(__FILE__) . '/../Model/Paper.php';
include_once dirname(__FILE__) . '/../Model/Word.php';

/**
 * Parser to parse paper objects.
 */
class XMLPaperParser implements Parser
{
    /**
     * Papers that were written by the Authors to be parsed.
     *
     * @var ModelSet
     */
    public $paper;
    public $word;

    /**
     * Parses the XML and returns a paper object.
     *
     * @param XML document of the paper.
     *
     * @return paper Returns an Author populated with data from $XML.
     */

    public function parseObject($XML)
    {
        $paper = new Paper();
        $paper->authors = explode('; ', $XML->authors);

        $paper->title = $XML->title->__toString();
        $paper->identifier = $XML->title->__toString();
        if (is_array($XML->thesaurusterms->term) || is_object($XML->thesaurusterms->term)) {

            foreach ($XML->thesaurusterms->term as $term) {

                array_push($paper->keywords, $term->__toString());
            }

        }

        $paper->abstract = $XML->abstract->__toString();
        $paper->conference = $XML->pubtitle->__toString();
        $paper->download = $XML->mdurl->__toString();
        $paper->bibtex = $this->parseBibtextLinkFromDownload($XML->arnumber->__toString());
        //set as null until we can extract text
        $paper->fullWords = null;
//CALL HERE 
        $word = new Word();
        $word->parseWord();
        return $paper;

    }

    /**
     * Returns the link to the citation by extracting the article number.
     *
     * @param Arnumber $arnumber The download link of the paper.
     *
     * @return string Returns the string representation of bibtex link.
     */
    function parseBibtextLinkFromDownload($arnumber)
    {

        $link = "http://ieeexplore.ieee.org/document/" . $arnumber . "/citations";

        return $link;
    }

    /**
     * Serializes an paper to JSON.
     *
     * @param Paper $Paper The Paper to be serialized.
     *
     * @return array Returns the JSON representation of the Paper.
     */
    function serializeObject($Paper)
    {

        // define a look-up table of relevant Paper info
        $json = [
             "title" => $Paper->title,
             "bibtex" => $Paper->bibtex,
             "download" => $Paper->download,
             "pdf" => $Paper->pdf,
             "fullWords" => $Paper->fullWords,
             "frequentWords" => $Paper->frequentWords,
             "authors" => $Paper->authors,
             "keywords" => $Paper->keywords,
             "abstract" => $Paper->abstract,
             "conference" => $Paper->conference
        ];
        return $json;
    }
}
?>

<?php
include_once dirname(__FILE__) . '/Parser.php';
include_once dirname(__FILE__) . '/PDFParser.php';
include_once dirname(__FILE__) . '/WordParser.php';
include_once dirname(__FILE__) . '/../Model/ModelSet.php';

include_once dirname(__FILE__) . '/../Model/Paper.php';
include_once dirname(__FILE__) . '/../Model/Word.php';

use \ModelSet as ModelSet;

/**
 * Parser to parse paper objects.
 */
class XMLPaperParser implements Parser{
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

    public function parseObject($XML){
        $paper = new \Paper();
        $PDFParser =  new \PDFParser();
        $word = new WordParser();

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

        $paper->pdf = $PDFParser->getPDFLinkFromIEEE($XML->pdf->__toString());

        $text = $PDFParser->getTextFromPDF($paper->pdf, $paper->abstract);

        if($text == "PDF not parsed"){
                $paper->fullWords = $paper->abstract;
        } else {
                $paper->fullWords = $text;
        }

        $paper->frequentWords = $word->parseObject ($paper->fullWords,$paper->title);
        return $paper;
    }

    /**
     * Returns the link to the citation by extracting the article number.
     *
     * @param Arnumber $arnumber The download link of the paper.
     *
     * @return string Returns the string representation of bibtex link.
     */
    function parseBibtextLinkFromDownload($arnumber){

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
    function serializeObject($paper){

        // define a look-up table of relevant Paper info
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
            "fullWords" => $paper->fullWords,
            "frequentWords" => array_map([$wordParser, "serializeObject"], $paper->frequentWords),
            "authors" => $paper->authors,
            "keywords" => $paper->keywords,
            "abstract" => $paper->abstract,
        ];
        
        return $json;
    }
}
?>

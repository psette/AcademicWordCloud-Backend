<?php
include_once dirname(__FILE__) . '/Parser.php';
include_once dirname(__FILE__) . '/PDFParser.php';

include_once dirname(__FILE__) . '/../Model/Paper.php';


/**
 * Parser to parse paper objects.
 */
class XMLPaperParser implements Parser
{
    /**
    * Tracks that were written by the Authors to be parsed.
    *
    * @var ModelSet
    */
    var $paper;

    /**
     * Parses the XML and returns a paper object.
     *
     * @param XML document of the paper.
     *
     * @return paper Returns an Author populated with data from $json.
     */


    function parseObject($XML)
    {
        $paper = new \Paper();
        $PDFParser =  new \PDFParser();

        $paper->authors = explode('; ', $XML->authors);

        $paper->title = $XML->title->__toString();
        $paper->identifier = $XML->title->__toString();
        if (is_array($XML->thesaurusterms->term) || is_object($XML->thesaurusterms->term)){

            foreach ($XML->thesaurusterms->term as $term) {

                array_push($paper->keywords, $term->__toString());
            }

        }

        $paper->abstract = $XML->abstract->__toString();
        $paper->conference = $XML->pubtitle->__toString();

        $paper->pdf = $PDFParser->getPDFLinkFromIEEE($XML->pdf->__toString());

        $paper->fullWords = $PDFParser->getTextFromPDF($paper->pdf );

        return $paper;

    }
    /**
     * Serializes an paper to JSON.
     *
     * @param Author $Author The Author to be serialized.
     *
     * @return array Returns the JSON representation of the Author.
     */
    function serializeObject($Author)
    {
    //     // declare a TrackParser and LyricsParser for storing information about the Author and words
    //     $paperParser = new PaperParser();
    //     $paperParser->Author = $Author;
    //     $paperParser = new LyricParser();
    //     $paperParser->tracks = $Author->tracks;
    //     $paperArray = [];
    //     if (!is_null($Author->tracks))
    //     {
    //         // No "map" function for SplObjectStorage, so must map values manually
    //         foreach ($Author->papers as $paper)
    //         {
    //             $json = $paperParser->serializeObject($paper);
    //             $paperArray[] = $json;
    //         }
    //     }
    //     // define a look-up table of relevant Author info
    //     $json = [
    //          "name" => $Author->name,
    //          "identifier" => $Author->identifier,
    //          "imageURL" => $Author->imageURL,
    //          "tracks" => $paperArray,
    //          "frequentLyrics" => array_map([$paperParser, "serializeObject"], $Author->frequentWords ?: []),
    //     ];
    //     return $json;
    // }
    }
}
?>

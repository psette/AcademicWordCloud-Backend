<?php

namespace App\Http\Controllers;

include_once dirname(__FILE__) . '/../../Parsers/XMLPaperParser.php';

include_once dirname(__FILE__) . '/Controller.php';
include_once dirname(__FILE__) . '/ACMServer.php';

use Illuminate\Http\Request;
use \XMLPaperParser as XMLPaperParser;

/**
 * The class handling the Server logic.
 */
class Server extends Controller
{

    /*
     * Search for authors matching provided name.
     *
     * @param author $author to search for
     * @codeCoverageIgnore
     * @return array of paper objects
     */
    // @codeCoverageIgnoreStart
    public function get_IEEE_file($author)
    {
        // get the contents of the wikia search
        $location = "https://ieeexplore.ieee.org/gateway/ipsSearch.jsp?au=" . urlencode($author);

        $file = @simplexml_load_file($location);

        if ($file == false) {

            return "FILE IS NOT FOUND";
        }

        return $file;
    }
    // @codeCoverageIgnoreEnd

    /*
     *  Parse gieven XMLpaper into paper object array
     *
     * @param XML file $file found in search
     * @return array of paper objects
     */
    public function parseXMLObject($file, $maximumPaperCount)
    {

        $XMLPaperParser = new XMLPaperParser();
        $papers = array();
        $progress = 0;
        foreach ($file->document as $document) {
            $progress++;
            if($progress >= $maximumPaperCount){
                return $papers;
            }
            $paper = $XMLPaperParser->parseObject($document);
            $papers[] = $paper;
        }

        return $papers;

    }

    /*
     * Search for authors matching provided text.
     *
     * @param Request $request
     * @param string $author
     *
     * @return JSON-encoded authors array.
     *
     */
    public function searchAuthors(Request $request, $author)
    {
        $searchType = $request->input('type');
        $maximumPaperCount = (int) ($request->input('count'));

        if($maximumPaperCount % 2 === 0){

            $numACM = $maximumPaperCount  / 2;
            $numIEEE = $maximumPaperCount  / 2;

        } else {
            $numACM =  1 + $maximumPaperCount  / 2;
            $numIEEE = $maximumPaperCount  / 2;

        }

        $XMLPaperParser = new XMLPaperParser();

        $file = $this->get_IEEE_file($author);

        $papers = $this->parseXMLObject($file, $numIEEE);

        if( count( $papers ) < $numIEEE){

            $numACM = $maximumPaperCount  - count( $papers );

        }

        $ACMpapers = ACMServer::searchPapers($author, $searchType, $numACM);

        if( count( $ACMpapers ) + count( $papers ) < $maximumPaperCount){

            $mergeTarget = array_merge($this->parseXMLObject($file, $maximumPaperCount), $papers);
            $papers = array_unique($mergeTarget);


        }

        if(is_null($papers) && is_null($ACMpapers)){

            return false;

        } else if(is_null($papers)){

            $serialize =  $ACMpapers;

        } else if(is_null($ACMpapers)){

            $serialize = $papers;

        } else {

            array_merge($papers, $ACMpapers);

            $serialize = $papers;
        }

        // Encode paper objects to JSON to send to client.
        $serialized = array_map([$XMLPaperParser, "serializeObject"], $serialize);
        $encoded = json_encode($serialized);

        switch (json_last_error()) {
            case JSON_ERROR_NONE:
                break;
            case JSON_ERROR_DEPTH:
                echo ' - Maximum stack depth exceeded';
                break;
            case JSON_ERROR_STATE_MISMATCH:
                echo ' - Underflow or the modes mismatch';
                break;
            case JSON_ERROR_CTRL_CHAR:
                echo ' - Unexpected control character found';
                break;
            case JSON_ERROR_SYNTAX:
                echo ' - Syntax error, malformed JSON';
                break;
            case JSON_ERROR_UTF8:
                echo ' - Malformed UTF-8 characters, possibly incorrectly encoded';
                break;
            default:
                echo ' - Unknown error';
                break;
        } // Allow cross-origin-requests so javascript can make requests.

        return response($encoded, 200)
            ->header('Content-Type', 'application/json')
            ->header('Access-Control-Allow-Origin', '*');
    }

}


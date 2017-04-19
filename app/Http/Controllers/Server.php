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

    public function getProgress(Request $request) {
           return $_SESSION["numPapersLeft"] / $_SESSION["maximumPaperCount"];
    }


    /*
     * Search for authors matching provided name.
     *
     * @param author $author to search for
     * @codeCoverageIgnore
     * @return array of paper objects
     */
    // @codeCoverageIgnoreStart
    public function get_IEEE_file($type, $param)
    {
        switch ($type) {
            case 'name':
                // get the contents of the wikia search
                $location = "https://ieeexplore.ieee.org/gateway/ipsSearch.jsp?au=" . urlencode($param);
                break;

            case 'keyword':
                // get the contents of the wikia search
                $location = "https://ieeexplore.ieee.org/gateway/ipsSearch.jsp?md=" . urlencode($param);
                break;

            case 'conf':
                // get the contents of the wikia search
                $location = "https://ieeexplore.ieee.org/gateway/ipsSearch.jsp?jn=" . urlencode($param);
                break;

            default:
                echo "Error in getting IEEE file";
                break;
        }

        $file = @simplexml_load_file($location);

        if ($file == false) {

            return "FILE IS NOT FOUND";
        }

        return $file;
    }
    // @codeCoverageIgnoreEnd

    /*
     *  Parse gieven XMLpaper into paper object array
     * @param XML file $file found in search
     * @return array of paper objects
     */
    public function parseXMLObject($file, $papersToSearch)
    {

        $XMLPaperParser = new XMLPaperParser();
        $papers = array();
        $progress = 0;
        foreach ($file->document as $document) {
            $progress++;

            $_SESSION["numPapersLeft"] = $_SESSION["numPapersLeft"] - 1;
            if($progress >= $papersToSearch ){
                return $papers;
            }
            $paper = $XMLPaperParser->parseObject($document);
            $papers[] = $paper;
        }

        return $papers;

    }

    public function search(Request $request, $term)
    {

        session_start();

        $searchType = $request->input('type');
        $_SESSION["maximumPaperCount"] = (int) ($request->input('count'));

        $_SESSION["numPapersLeft"] = $_SESSION["maximumPaperCount"];


        $numACM = ceil( $_SESSION["maximumPaperCount"]  / 2 ) ;
        $numIEEE = floor( $_SESSION["maximumPaperCount"]  / 2 );


        $XMLPaperParser = new XMLPaperParser();

        $file = $this->get_IEEE_file($searchType, $term);
        $papers = null;

        if (strcmp($searchType, "conf") == 0)
        {
            $papers = $this->parseIEEEPaperTitles($file);
        }
        else
        {
            $file = $this->get_IEEE_file($searchType, $term);
            $papers = $this->parseXMLObject($file, $numIEEE);
        }

        $numACM = $_SESSION["maximumPaperCount"]  - count($papers);

        $ACMpapers = ACMServer::searchPapers($term, $searchType, $numACM);

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

        $serialized = $serialize;

        // Only need to serialize papers if not searching by conference.
        // Conference searches are just strings so no need to serialize that.
        if (strcmp($searchType, "conf") != 0)
        {
            // Encode paper objects to JSON to send to client.
            $serialized = array_map([$XMLPaperParser, "serializeObject"], $serialize);
        }

        $bytes = $this->utf8ize($serialized);
        $encoded = json_encode($bytes);

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

    public function utf8ize($d)
    {
        if (is_array($d)) {
            foreach ($d as $k => $v) {
                $d[$k] = $this->utf8ize($v);
            }
        } else if (is_string($d)) {
            return utf8_encode($d);
        }
        return $d;
    }

    public function parseIEEEPaperTitles($file) {
        $papers = array();
        foreach ($file->document as $document) {
            $papers[] = $document->title->__toString();;
        }

        return $papers;
    }
}



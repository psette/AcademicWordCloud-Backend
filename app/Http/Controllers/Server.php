<?php

namespace App\Http\Controllers;

include_once dirname(__FILE__) . '/../../Model/Track.php';
include_once dirname(__FILE__) . '/../../Model/Artist.php';
include_once dirname(__FILE__) . '/../../Model/Lyric.php';

include_once dirname(__FILE__) . '/../../Parsers/XMLPaperParser.php';
include_once dirname(__FILE__) . '/../../Parsers/TrackParser.php';
include_once dirname(__FILE__) . '/../../Parsers/ArtistParser.php';
include_once dirname(__FILE__) . '/../../Parsers/LyricParser.php';

include_once dirname(__FILE__) . '/Controller.php';

use Illuminate\Http\Request;

use \Artist as Artist;
use \Track as Track;
use \Lyric as Lyric;

use \XMLPaperParser as XMLPaperParser;
use \authorParser as ArtistParser;
use \TrackParser as TrackParser;
use \LyricParser as LyricParser;

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

       if ($file == FALSE){

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
    public function parseXMLObject($file){

        $XMLPaperParser = new XMLPaperParser();

        $papers = array();
        ob_end_flush();

        echo "Parsing $file->totalfound files. \n";

        ob_start();
        $progress = 0;
        foreach ($file->document as $document) {
            ob_end_flush();
            echo "Parsing file: $progress. \n";
            ob_start();
            $progress++;
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
        // For IEEE testing keep true

        $IEEE = true;
        $XMLPaperParser = new XMLPaperParser();

        if($IEEE){
            $file = $this->get_IEEE_file($author);
        }

        $papers = $this->parseXMLObject($file);


        // Encode paper objects to JSON to send to client.
        $serialized = array_map([$XMLPaperParser, "serializeObject"], $papers);
        $encoded = json_encode($serialized);

        // Allow cross-origin-requests so javascript can make requests.
        return response($encoded, 200)
                  ->header('Content-Type', 'application/json')
                  ->header('Access-Control-Allow-Origin', '*');
    }
}

?>

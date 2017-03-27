<?php

namespace App\Http\Controllers;

include_once dirname(__FILE__) . '/../../Model/Paper.php';
include_once dirname(__FILE__) . '/../../Model/Author.php';
include_once dirname(__FILE__) . '/../../Model/Word.php';

include_once dirname(__FILE__) . '/../../Parsers/PaperParser.php';
include_once dirname(__FILE__) . '/../../Parsers/AuthorParser.php';
include_once dirname(__FILE__) . '/../../Parsers/WordParser.php';

include_once dirname(__FILE__) . '/Controller.php';

use Illuminate\Http\Request;

use \Author as Author;
use \Paper as Paper;
use \Word as Word;

use \AuthorParser as AuthorParser;
use \PaperParser as PaperParser;
use \WordParser as WordParser;

/**
 * The class handling the Server logic.
 */
class Server extends Controller
{

    /*
     * Search for authors matching provided text.
     *
     * @param Author $author to search for
     * @codeCoverageIgnore
     * @return String of lyrics.wikia.com authors page
     *         Null if page cannot be rendered
     *
     *
     */
    // @codeCoverageIgnoreStart
    public function getAuthorPageString($author)
    {
        // get the contents of the wikia search
        $location = "http://lyrics.wikia.com/wikia.php?controller=LyricsApi&method=searchArtist&query=" . urlencode($author);
        $file = @file_get_contents(html_entity_decode($location));
        return $file;
    }
     // @codeCoverageIgnoreEnd

    /*
     * Search for authors matching provided text.
     *
     * @param Request $request
     * @param string $author
     *
     * @return JSON-encoded Authors array.
     *
     */
    public function searchAuthors(Request $request, $author)
    {
        $authors = [];

        $file = $this->getAuthorPageString($author);

        if ($file == FALSE)
        {
            return $authors;
        }

        $json = json_decode($file, true);

        $authorParser = new AuthorParser();
        $paperParser = new PaperParser();
        $wordParser = new WordParser();

        // If results key does not exist, an error occured so return empty array.
        if (!array_key_exists("result", $json))
        {
            $response = json_encode($authors);
            return $response;
        }

        $authorCount = 0;

        foreach ($json["result"] as $authorJSON)
        {
            $author = $authorParser->parseObject($authorJSON);

            if (array_key_exists("songs", $authorJSON))
            {
                // Ensure no more than 3 authors are returned
                $authorCount = $authorCount + 1;
                 // @codeCoverageIgnoreStart
                if ($authorCount > 3)
                {
                    break;
                }
                // @codeCoverageIgnoreEnd
                $array = $authorJSON["songs"];
                foreach ($authorJSON["songs"] as $paperJSON)
                {
                    // Parse limited trackJSON to obtain url value.
                    // The full track JSON will be fetched + parsed in fetchTrack() (including lyrics, which aren't present here)
                    $tempPaper = $wordParser->parseObject($paperJSON);

                    if (!is_null($tempTrack))
                    {
                        $paper = $this->fetchPaper($tempPaper->url, $author);

                        if (!is_null($track))
                        {
                            $author->papers->attach($paper);
                        }
                    }
                }

                // Determine frequent words for an Author from the papers.
                $author->frequentWords = Paper::frequentWordsFromPapers($author->papers);

                array_push($authors, $author);
            }
        }

        // Encode Author objects to JSON to send to client.
        $serialized = array_map([$authorParser, "serializeObject"], $authors);
        $encoded = json_encode($serialized);

        // Allow cross-origin-requests so javascript can make requests.
        return response($encoded, 200)
                  ->header('Content-Type', 'application/json')
                  ->header('Access-Control-Allow-Origin', '*');
    }

    /*
     * Fetch track metadata for a given track URL.
     *
     * @param string $url
     * @param Author $author
     *
     * @return Track.
     *
     */
    private function fetchPaper($url, $author)
    {
        $file = @file_get_contents($url);
        if ($file == FALSE)
        {
            return null;
        }

        $json = json_decode($file, true);
        // @codeCoverageIgnoreStart
        // If no result key, there was an error so return null.
        if (!array_key_exists("result", $json))
        {
            return null;
        }
         // @codeCoverageIgnoreEnd

        $trackJSON = $json["result"];

        $paperParser = new PaperParser();
        $paperParser->author = $author;

        $paper = $paperParser->parseObject($trackJSON);

        return $paper;
    }
}

?>

<?php

namespace App\Http\Controllers;

include_once dirname(__FILE__) . '/Server.php';
/**
 * The class handling the Server logic.
 */
class ServerHelper
{
     /*
     * Search for artists matching provided text.
     *
     * @param Artist $artist to search for
     * @return String of lyrics.wikia.com artist page
     *
     */
    public function getURLsafe($url)
    {
        $file = @file_get_contents($url);
        if ($file == FALSE)
        {
            return null;
        }
        return $file;
    }
    /*
     * Search for artists matching provided text.
     *
     * @param Artist $artist to search for
     * @return String of lyrics.wikia.com artist page
     *
     */
    public function getArtistPageString($artist)
    {
        $location = "http://lyrics.wikia.com/wikia.php?controller=LyricsApi&method=searchArtist&query=" . urlencode($artist);
        $file = @file_get_contents(html_entity_decode($location));
        return $file;
    }
}

?>

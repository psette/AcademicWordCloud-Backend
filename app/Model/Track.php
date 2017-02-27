<?php

/**
 * A musical track composed by an artist.
 */
class Track
{
    /**
     * The name of the Track.
     *
     * @var string
     */
    var $name;

    /**
     * The unique identifier representing the Track.
     *
     * @var string
     */
    var $identifier;

    /**
     * The metadata URL of the Track.
     *
     * @var string
     */
    var $url;

    /**
     * The Tracks's most frequently used Lyrics, sorted by frequency.
     *
     * @var array
     */
    var $frequentLyrics;

    /**
     * The Artist that composed this Track.
     *
     * @var Artist
     */
    var $artist;

    function __construct() 
    {
        $this->frequentLyrics = [];
    }
}

<?php

/**
 * A musical artist.
 */
class Artist
{
    /**
     * The name of the Artist.
     *
     * @var string
     */
    var $name;

    /**
     * The unique identifier representing the Artist.
     *
     * @var string
     */
    var $identifier;

    /**
     * A URL pointing to an image representing the Artist.
     *
     * @var string
     */
    var $imageURL;

    /**
     * The Tracks composed by the Artist.
     *
     * @var ModelSet
     */
    var $tracks;

    /**
     * The Artist's most frequently used Lyrics, sorted by frequency.
     *
     * @var array
     */
    var $frequentLyrics;
}

?>

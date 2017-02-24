<?php

namespace App;

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
     * @var int
     */
    var $identifier;

    /**
     * The Tracks composed by the Artist.
     *
     * @var TrackStorage
     */
    var $tracks;

    /**
     * The Artist's most frequently used Lyrics, sorted by frequency. 
     *
     * @var array
     */
    var $frequentLyrics;
}

/**
 * SplObjectStorage subclass for storing Artists. Uniqueness is determined by Artist's 'identifier' property.
 */
class ArtistStorage extends SplObjectStorage 
{
    public function getHash($artist) 
    {
        return $artist->identifier;
    }
}
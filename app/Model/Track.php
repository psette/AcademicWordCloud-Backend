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
}

/**
 * SplObjectStorage subclass for storing Tracks. Uniqueness is determined by Track's 'identifier' property.
 */
class TrackStorage extends \SplObjectStorage
{
    public function getHash($track)
    {
        return $track->identifier;
    }
}

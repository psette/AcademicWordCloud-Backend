<?php

namespace App;

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
     * @var int
     */
    var $identifier;

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
class TrackStorage extends SplObjectStorage 
{
    public function getHash($track) 
    {
        return $track->identifier;
    }
}
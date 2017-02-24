<?php

namespace App;

/**
 * A lyric from one or more musical tracks.
 */
class Lyric
{
    /**
     * The string representation of the Lyric.
     *
     * @var string
     */
    var $stringValue;

    /**
     * The frequency of the Lyric.
     *
     * @var int
     */
    var $frequency;

    /**
     * The Tracks that contain the Lyric.
     *
     * @var TrackStorage
     */
    var $tracks;
}

/**
 * SplObjectStorage subclass for storing Lyrics. Uniqueness is determined by Lyric's 'stringValue' property.
 */
class LyricStorage extends SplObjectStorage 
{
    public function getHash($lyric) 
    {
        return $lyric->stringValue;
    }
}
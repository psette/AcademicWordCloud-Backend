<?php

include_once dirname(__FILE__) . '/../Model/ModelSet.php';

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
     * The unique identifier representing the Lyric.
     *
     * @var string
     */
    var $identifier;

    /**
     * The frequency of the Lyric.
     *
     * @var int
     */
    var $frequency;

    /**
     * The Tracks that contain the Lyric.
     *
     * @var ModelSet
     */
    var $tracks;

    function __construct() 
    {
        $this->frequency = 0;
        $this->tracks = new ModelSet();
    }

    static function compareByFrequency($a, $b)
    {
        if ($a->frequency == $b->frequency) 
        {
            return 0;
        }

        return ($a->frequency < $b->frequency) ? -1 : 1;
    }
}
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

    /**
     * The Lyric constructor.
     */
    function __construct() 
    {
        $this->frequency = 0;
        $this->tracks = new ModelSet();
    }

    /**
     * Comparator function to sort Lyrics by frequency.
     *
     * @param Lyric $a
     * @param Lyric $b
     *
     * @return int.
     *
     */
    static function compareByFrequency($a, $b)
    {
        if ($a->frequency == $b->frequency) 
        {
            return 0;
        }

        return ($a->frequency < $b->frequency) ? 1 : -1;
    }
}
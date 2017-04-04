<?php

include_once dirname(__FILE__) . '/../Model/ModelSet.php';

/**
 * A word from one or more papers.
 */
class Word
{
    /**
     * The string representation of the Word.
     *
     * @var string
     */
    var $stringValue;

    /**
     * The unique identifier representing the Word.
     *
     * @var string
     */
    var $identifier;

    /**
     * The frequency of the Word.
     *
     * @var int
     */
    var $frequency;

    /**
     * The Papers that contain the Word.
     *
     * @var ModelSet
     */
    var $papers;

    /**
     * The Word constructor.
     */
    function __construct() 
    {
        $this->frequency = 0;
        $this->papers = new ModelSet();
    }

    /**
     * Comparator function to sort Words by frequency.
     *
     * @param Word $a
     * @param Word $b
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
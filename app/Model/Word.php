<?php

include_once dirname(__FILE__) . '/../Model/ModelSet.php';

/**
 * A word from one or more musical papers.
 */
class Word
{
    /**
     * The string representation of the word.
     *
     * @var string
     */
    var $stringValue;

    /**
     * The unique identifier representing the word.
     *
     * @var string
     */
    var $identifier;

    /**
     * The frequency of the word.
     *
     * @var int
     */
    var $frequency;

    /**
     * The papers that contain the word.
     *
     * @var ModelSet
     */
    var $papers;

    /**
     * The word constructor.
     */
    function __construct() 
    {
        $this->frequency = 0;
        $this->papers = new ModelSet();
    }

    /**
     * Comparator function to sort words by frequency.
     *
     * @param word $a
     * @param word $b
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
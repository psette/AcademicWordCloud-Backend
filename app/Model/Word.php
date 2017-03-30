<?php

/**
 * Words from Paper
 */
class Word
{
    /**
     * Word in Paper.
     *
     * @var string
     */
    var $stringValue;

    /**
     * The frequency of the word in the Paper.
     *
     * @var int
     */
    var $frequency;

    /**
     * All of the papers that the word appears in.
     *
     * @var papers
     */
    var $papers;

    /**
     * A Map of every paper with the word and the frequency of the word.
     *
     * @var paperWordFrequency
     */
    var $paperWordFrequency;

    /**
     * The Word constructor.
     */
    function __construct(){
        $this->papers = [];
        $this->paperWordFrequency = [];
    }


}

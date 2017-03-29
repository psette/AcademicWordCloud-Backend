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
    var $identifier;

    /**
     * The frequency of the word in the Paper.
     *
     * @var int
     */
    var $frequencyOfWord;

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

    function parseWord($paper){
        //call parse function on paper to get array of words

        //parse the string -> turn into array of words
        //$comp = preg_split('/ +/', $var);
        $paperName = "Paper Identifer goes hereeee";

        $s="Your name is one of the most important words for you to know. You hear your name whenever
a person says it. We like it if it is whispered to you in a nice tone for something you have done.
We love hearing the sound of our name repeated over and over.";
      //  $arr = explode(" ", $s);
        $wordList = preg_split('/ +/', $s);

        $wordFrequencyInPaper = array_count_values(str_word_count($s, 1)) ;
        $words = [];
        foreach($wordFrequencyInPaper as $nugget => $value){
            $word = new Word();
            $word->identifier= $nugget;
            $word->frequencyOfWord = $value;
            array_push($words, $word);
            array_push($word->papers, $paper);
        }
        return $words;

    }

}

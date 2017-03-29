<?php
include_once dirname(__FILE__) . '/../Model/Word.php';

class WordParser {
    function parseWord($paperText,$paperName ){
        //call parse function on paper to get array of words

        //parse the string -> turn into array of words
        //$comp = preg_split('/ +/', $var);
        $paperName = "Paper Identifer goes hereeee";

        $s="Your name is one of the most important words for you to know. You hear your name whenever
a person says it. We like it if it is whispered to you in a nice tone for something you have done.
We love hearing the sound of our name repeated over and over.";
      //  $arr = explode(" ", $s);
      //  $wordList = preg_split('/ +/', $s);

        $wordFrequencyInPaper = array_count_values(str_word_count($paperText, 1)) ;
        $words = [];
        foreach($wordFrequencyInPaper as $nugget => $value){
            $word = new Word();
            $word->istringValue= $nugget;
            $word->frequency = $value;
            array_push($words, $word);
            array_push($word->papers, $paperName);
        }
        return $words;

    }
}
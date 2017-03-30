<?php
include_once dirname(__FILE__) . '/../Model/Word.php';

class WordParser {

   // var allWords;
    function parseWord($paperText,$paperName ){


        $wordFrequencyInPaper = array_count_values(str_word_count($paperText, 1)) ;
        $words = [];
        foreach($wordFrequencyInPaper as $nugget => $value){
            $word = new Word();
            // if(allWords[nugget]){

            //     add it
            // } else {

            // increment}

            $word->istringValue= $nugget;
            $word->frequency = $value;
            array_push($words, $word);
            array_push($word->papers, $paperName);
        }
        return $words;

    }
}
<?php
include_once dirname(__FILE__) . '/../Model/Word.php';

class WordParser {
    function parseWord($paperText,$paperName ){
        //call parse function on paper to get array of words

        //parse the string -> turn into array of words

        $wordFrequencyInPaper = array_count_values(str_word_count($paperText, 1)) ;
        $words = [];
        foreach($wordFrequencyInPaper as $nugget => $value){
            $word = new Word();
            $word->stringValue= $nugget;
            $word->frequency = $value;
            array_push($word->papers, $paperName);
            array_push($words, $word);
        }
        return $words;
    }
     /**
     * Serializes a word to JSON.
     *
     * @param word $lyric Lyric to be serialized.
     *
     * @return array Returns the JSON representation of the word.
     */
    function serializeObject($word)
    {
        $json = [
             "stringValue" => $word->stringValue,
             "frequency" => $word->frequency,
             "paper" => $word->papers
        ];
        return $json;
    }
}

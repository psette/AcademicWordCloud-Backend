<?php
include_once dirname(__FILE__) . '/../Model/Word.php';

class WordParser {

    function cmp($a, $b){
            return $a->frequency <= $b->frequency;
    }

    function parseWord($paperText,$paperName ){

        $wordFrequencyInPaper = array_count_values(str_word_count($paperText, 1)) ;
        arsort( $wordFrequencyInPaper );
        $words = [];
        foreach($wordFrequencyInPaper as $nugget => $value){
            $word = new Word();
            $word->stringValue= strtolower($nugget);
            $word->frequency = $value;
            array_push($word->papers, $paperName);
            array_push($words, $word);
        }
        usort($words, array($this, "cmp"));
        return $words;
    }
     /*
     * Serializes a word to JSON.
     *
     * @param word $lyric Lyric to be serialized.
     *
     * @return array Returns the JSON representation of the word.
     */
    function serializeObject($word){
        $json = [
             "stringValue" => $word->stringValue,
             "frequency" => $word->frequency,
             "paper" => $word->papers
        ];
        return $json;
    }
}

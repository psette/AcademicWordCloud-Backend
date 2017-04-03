<?php

include_once __DIR__  . '/Parser.php';
include_once __DIR__  . '/../Model/Word.php';

/**
 * Parser to parse Word objects.
 */
class WordParser implements Parser
{
    /**
    * The Papers the word to be parsed belong to.
    *
    * @var ModelSet of Papers
    */
    var $papers;

    /**
     * Parses the words string and returns Words.
     *
     * @param string $words The words in a Paper.
     *
     * @return array Returns an array of Words from $json.
     */
    function parseObject($string)
    {
        // Split $words into words separated by non-letters (except ' and -).
        $string = preg_replace('/[^a-z0-9]+/i', ' ', $string);
        $terms = explode(" ", $string);
        
        $words = [];
        foreach ($terms as $term)
        {
            $term = strtolower($term);

            $word = null;
            if (array_key_exists($term, $words))
            {
                $word = $words[$term];
            }
            else
            {
                $word = new Word();
                $word->stringValue = $term;
                $word->identifier = $term;
                if (!is_null($this->papers))
                {
                    $word->papers = $this->papers;
                }
            }
            $word->frequency = $word->frequency + 1;
            $words[$term] = $word;
        }
        usort($words, ["Word", "compareByFrequency"]);

        return $words;
    }

    /**
     * Serializes a Word to JSON.
     *
     * @param Word $word Word to be serialized.
     *
     * @return array Returns the JSON representation of the Word.
     */
    function serializeObject($word)
    {
        $json = [
             "stringValue" => $word->stringValue,
             "frequency" => $word->frequency,
include_once dirname(__FILE__) . '/../Model/Word.php';

class WordParser {

    function cmp($a, $b){
            return $a->frequency <= $b->frequency;
    }

    function parseWord($paperText,$paperName ){

        $paperText = preg_replace("/[^A-Za-z0-9 ]/", ' ', strtolower($paperText) );

        $wordFrequencyInPaper = array_count_values(str_word_count($paperText, 1)) ;
        arsort( $wordFrequencyInPaper );
        $words = [];
        foreach($wordFrequencyInPaper as $nugget => $value){
            $word = new Word();
            $word->stringValue= $nugget;
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
?>
=======

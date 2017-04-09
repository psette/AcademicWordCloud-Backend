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
        ];
        return $json;
    }
}
?>

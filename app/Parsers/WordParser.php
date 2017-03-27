<?php

include_once __DIR__  . '/Parser.php';
include_once __DIR__  . '/../Model/Word.php';

/**
 * Parser to parse Lyric objects.
 */
class WordParser implements Parser
{
    /**
    * The Papers the lyrics to be parsed belong to.
    *
    * @var array of Strings
    */
    var $papers;

    /**
     * Parses the lyrics string and returns Lyrics.
     *
     * @param string $wordsString The lyrics for a track.
     *
     * @return array Returns an array of Lyrics from $json.
     */
    function parseObject($wordsString)
    {
        var_dump($wordsString);
        // Split $lyrics into words separated by non-letters (except ' and -).
        $wordArray = preg_replace('/[^a-z0-9]+/i', ' ', $wordsString);
        var_dump($wordArray);
        $wordArray = explode(" ", $wordArray);

        $words = [];
        foreach ($wordArray as $wordElement)
        {
            $wordElement = strtolower($wordElement);

            $word = null;
            if (array_key_exists($wordElement, $words))
            {
                $word = $words[$wordElement];
            }
            else
            {
                $word = new Word();
                $word->stringValue = $wordElement;
                $lyric->identifier = $wordElement;
                if (!is_null($this->papers))
                {
                    $word->papers = $this->papers;
                }
            }
            $word->frequency = $word->frequency + 1;
            $words[$wordElement] = $word;
        }
        usort($words, ["Word", "compareByFrequency"]);

        return $words;
    }

    /**
     * Serializes a Word to JSON.
     *
     * @param Lyric $word Word to be serialized.
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

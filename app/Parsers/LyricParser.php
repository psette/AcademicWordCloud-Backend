<?php

include_once dirname(__FILE__) . '/Parser.php';
include_once dirname(__FILE__) . '/../Model/Lyric.php';

class LyricParser implements Parser
{
    var $tracks;

    /**
     * Parses the lyrics string and returns Lyrics.
     *
     * @param array $json A JSON array of Lyrics.
     *
     * @return array Returns an array of Lyrics from $json.
     */
    function parseObject($lyrics)
    {
        // Split $lyrics into words separated by non-letters (except ' and -).
        $words = preg_split("/[^a-z\'-]+/", $lyrics, -1, PREG_SPLIT_NO_EMPTY);

        // Sanitize words into their root words.
        $sanitizedWords = array_map("sanitizeWord", $words);

        $lyrics = [];

        foreach ($sanitizedWords as $word) 
        {
            $lyric = $lyrics[$word];
            if (is_null($lyric))
            {
                $lyric = new Lyric();
                $lyric->stringValue = $word;
                $lyric->identifier = $word;

                if (!is_null($this->tracks))
                {
                    $lyric->tracks = $this->tracks;
                }
            }

            $lyric->frequency = $lyric->frequency + 1;

            $lyrics[$word] = $lyric;
        }

        usort($lyrics, ["Lyric", "compareByFrequency"]);

        return $lyrics;
    }

    /**
     * Serializes array of Lyrics to JSON.
     *
     * @param array $lyrics An array of Lyrics to be serialized.
     *
     * @return array Returns the JSON representation of the LyricStorage.
     */
    function serializeObject($lyric)
    {
        $json = [
             "stringValue" => $lyric->stringValue,
             "frequency" => $lyric->frequency,
        ];
        return $json;
    }

    private function sanitizeWord($word)
    {
        return $word;
    }
}

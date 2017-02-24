<?php

require('Parser.php');

class LyricParser implements Parser
{
    /**
     * Parses the JSON and returns Lyrics.
     *
     * @param array $json A JSON array of Lyrics.
     *
     * @return array Returns an array of Lyrics from $json.
     */
    function parseObject($json)
    {
        // To be implemented
    }

    /**
     * Serializes array of Lyrics to JSON.
     *
     * @param array $lyrics An array of Lyrics to be serialized.
     *
     * @return array Returns the JSON representation of the LyricStorage.
     */
    function serializeObject($lyrics)
    {
        $json = Array();

        foreach ($lyrics as $key)
        {
            $lyric = $lyrics[$key];

            array_push($json, array(
                "word" => $lyric->stringValue,
                "frequency" => $lyric->frequency,
            ));
        }
        return $json;
    }
}
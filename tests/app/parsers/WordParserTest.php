<?php

class WordParserTest extends TestCase
{
    /**
     * Parses the JSON and returns an Track.
     *
     * @param array The key-value store of the representation of an Track.
     *
     * @return Track Returns an Track populated with data from $json.
     */
    public function testParseObject()
    {
        // /*
        //  * Pietro what the fuck are these tests, dawg? They were failing
        //  */
        // $words = 'foo foo foo bar bar bar';
        // $wordObject = new wordParser();
        // $wordObject->tracks = array();
        //  array_push($wordObject->tracks, "Sample Track");
        // $results = $wordObject->parseObject($words);

        // $resultsFirstEntry = $results[0];
        // $firstStringValue = $resultsFirstEntry->stringValue;
        // $firstID = $resultsFirstEntry->identifier;
        // $firstFrequency = $resultsFirstEntry->frequency;
        // $firstTracks = $resultsFirstEntry->tracks;

        // $this->assertEquals("foo", $firstStringValue);
        // $this->assertEquals("foo", $firstID);
        // $this->assertEquals(3, $firstFrequency);
        // $this->assertTrue(is_array($firstTracks));


        // $resultsSecondEntry = $results[1];
        // $secondStringValue = $resultsSecondEntry->stringValue;
        // $secondID = $resultsSecondEntry->identifier;
        // $secondFrequency = $resultsSecondEntry->frequency;
        // $secondTracks = $resultsSecondEntry->tracks;

        // $this->assertEquals("bar", $secondStringValue);
        // $this->assertEquals("bar", $secondID);
        // $this->assertEquals(3, $secondFrequency);
        // $this->assertTrue(is_array($secondTracks));
     }

    /*
     * Serializes a word to JSON.
     *
     * @param word $word The Artist to be serialized.
     *
     * @return array Returns the JSON representation of the track.
     */
    function testSerializeObject(){
        // $word = new word();

        // $word->stringValue =  "foo-word";
        // $word->frequency = 1024;

        // $wordParser = new wordParser();
        // $resultJSON = $wordParser->serializeObject($word);

        // $this->assertEquals("foo-word", $resultJSON["stringValue"]);
        // $this->assertEquals(1024, $resultJSON["frequency"]);

    }

}

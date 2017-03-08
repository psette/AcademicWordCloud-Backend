<?php

class LyricParserTest extends TestCase
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
        $lyrics = 'foo foo foo bar bar bar';
        $LyricObject = new LyricParser();
        $LyricObject->tracks = array();
         array_push($LyricObject->tracks, "Sample Track");
        $results = $LyricObject->parseObject($lyrics);

        $resultsFirstEntry = $results[0];
        $firstStringValue = $resultsFirstEntry->stringValue;
        $firstID = $resultsFirstEntry->identifier;
        $firstFrequency = $resultsFirstEntry->frequency;
        $firstTracks = $resultsFirstEntry->tracks;

        $this->assertEquals("bar", $firstStringValue);
        $this->assertEquals("bar", $firstID);
        $this->assertEquals(3, $firstFrequency);
        $this->assertTrue(is_array($firstTracks));


        $resultsSecondEntry = $results[1];
        $secondStringValue = $resultsSecondEntry->stringValue;
        $secondID = $resultsSecondEntry->identifier;
        $secondFrequency = $resultsSecondEntry->frequency;
        $secondTracks = $resultsSecondEntry->tracks;

        $this->assertEquals("foo", $secondStringValue);
        $this->assertEquals("foo", $secondID);
        $this->assertEquals(3, $secondFrequency);
        $this->assertTrue(is_array($secondTracks));
     }

    /*
     * Serializes a lyric to JSON.
     *
     * @param lyric $lyric The Artist to be serialized.
     *
     * @return array Returns the JSON representation of the track.
     */
    function testSerializeObject(){
        $lyric = new Lyric();

        $lyric->stringValue =  "foo-lyric";
        $lyric->frequency = 1024;

        $LyricParser = new LyricParser();
        $resultJSON = $LyricParser->serializeObject($lyric);

        $this->assertEquals("foo-lyric", $resultJSON["stringValue"]);
        $this->assertEquals(1024, $resultJSON["frequency"]);

    }

}

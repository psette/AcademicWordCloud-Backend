<?php

class TrackParserTest extends TestCase
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
        $lyrics = "uncommon A. the";

        $fetchedFrequentLyrics = array(
            'stringValue'  =>  "UNCOMMON",
            'frequency' => 1
        );

        $stub = $this->createMock(LyricParser::class);
        $stub->method('parseObject')->willReturn($fetchedFrequentLyrics);

        $testInput = array(
             "name" => "foo-name",
             "url" => "foo-url",
                "lyrics" => $lyrics
            );

        $TrackObject = new TrackParser();
        $resultTrack = $TrackObject->parseObject($testInput);

        $fetchedTrackName = $resultTrack->name;
        $fetchedTrackImage = $resultTrack->url;
        $fetchedTrackLyrics = $resultTrack->lyrics;
        $fetchedFreqTrackLyrics = $resultTrack->frequentLyrics;

        $this->assertEquals("foo-name", $fetchedTrackName);
        $this->assertEquals("foo-url", $fetchedTrackImage);
        $this->assertEquals($lyrics, $fetchedTrackLyrics);

        $this->assertTrue(is_array($fetchedFreqTrackLyrics));
    }

}

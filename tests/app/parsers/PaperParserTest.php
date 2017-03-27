<?php

class PaperParserTest extends TestCase
{
    /**
     * Parses the JSON and returns an Paper.
     *
     * @param array The key-value store of the representation of an paper.
     *
     * @return paper Returns an paper populated with data from $json.
     */
    public function testParseObject()
    {
        // $lyrics = "uncommon A. the";

        // $fetchedFrequentLyrics = array(
        //     'stringValue'  =>  "UNCOMMON",
        //     'frequency' => 1
        // );

        // $stub = $this->createMock(LyricParser::class);
        // $stub->method('parseObject')->willReturn($fetchedFrequentLyrics);

        // $testInput = array(
        //      "name" => "foo-name",
        //      "url" => "foo-url",
        //         "lyrics" => $lyrics
        //     );

        // $paperObject = new paperParser();
        // $resultPaper = $paperObject->parseObject($testInput);

        // $fetchedPaperName = $resultPaper->name;
        // $fetchedPaperImage = $resultPaper->url;
        // $fetchedPaperLyrics = $resultPaper->lyrics;
        // $fetchedFreqpaperLyrics = $resultPaper->frequentLyrics;

        // $this->assertEquals("foo-name", $fetchedPaperName);
        // $this->assertEquals("foo-url", $fetchedPaperImage);
        // $this->assertEquals($lyrics, $fetchedPaperLyrics);

        // $this->assertTrue(is_array($fetchedFreqPaperLyrics));
    }

    /*
     * Serializes a paper to JSON.
     *
     * @param paper $paper The Artist to be serialized.
     *
     * @return array Returns the JSON representation of the paper.
     */
    function testSerializeObject()
    {
        // $arr = array();
        // $stub = $this->createMock(LyricParser::class);
        // $stub->method('serializeObject')->willReturn($arr);
        // $paper = new paper();
        // $paper->name =  "foo-paper";
        // $paper->identifier = "foo-ID";
        // $paper->lyrics= "Foo, foobar, barfoo";

        // $paperObject = new paperParser();
        // $resultJSON = $paperObject->serializeObject($paper);

        // $this->assertEquals("foo-paper", $resultJSON["name"]);
        // $this->assertEquals("foo-ID", $resultJSON["identifier"]);
        // $this->assertEquals("Foo, foobar, barfoo", $resultJSON["lyrics"]);

        // $this->assertTrue(is_array($resultJSON["frequentLyrics"]));
    }
}

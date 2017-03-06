<?php

class ArtistParserTest extends TestCase
{
	/**
     * Parses the JSON and returns an Artist.
     *
     * @param array The key-value store of the representation of an Artist.
     *
     * @return Artist Returns an Artist populated with data from $json.
     */
    public function testParseObject()
    {
    	// needs to test frequentLyrics
		$testInput = array(
		     "name" => "foo-name",
		     "small_image" => "http://small_image.html"
		);

    	$ArtistObject = new ArtistParser();
		$resultArtist = $ArtistObject->parseObject($testInput);

		$fetchedArtistName = $resultArtist->name;
		$fetchedArtistImage = $resultArtist->imageURL;

        $this->assertEquals("foo-name", $fetchedArtistName);
        $this->assertEquals("http://small_image.html", $fetchedArtistImage);
    }
}
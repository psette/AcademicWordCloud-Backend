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

		$testInput = array(
		     "name" => "foo-name",
		     "small_image" => "http://small_image.html",
		     "frequent_lyrics" => array(
		     	"stringValue" => "Foo",
		     	"frequency" => 10
		     )
	     );

	// create an artist object and invoke the function with the given input
    	$ArtistObject = new ArtistParser();
	$resultArtist = $ArtistObject->parseObject($testInput);

    // fetch the properties from the artist
    $fetchedArtistName = $resultArtist->name;
    $fetchedArtistImage = $resultArtist->imageURL;
    $fetchedArtistFrequency = $resultArtist->frequentLyrics;

	// test that the artist representation is complete with the parsed data
    $this->assertEquals($fetchedArtistName, "foo-name");
    $this->assertEquals($fetchedArtistImage, "http://small_image.html");
    $this->assertTrue(is_array($fetchedArtistFrequency));
    }

    /**
     * Serializes an Artist to JSON.
     *
     * @param artist $artist The Artist to be serialized.
     *
     * @return array Returns the JSON representation of the Artist.
     */
    function testSerializeObject()
    {
    	$testArtistName = "foo-artist";
    	$testArtistIdentifier = "foo-indentifier";
    	$testArtistImageURL = "https://foo.com";
    	$testArtistTracks = array("foo-track-1", "foo-track-2", "foo-track-3");
    	$testFrequentLyrics = array();

    	$testArtist = new Artist();
    	$testArtist->identifier = $testArtistIdentifier;
    	$testArtist->name = $testArtistName;
    	$testArtist->imageURL = $testArtistImageURL;
    	$testArtist->frequentLyrics = $testFrequentLyrics;
    	$testArtist->tracks = $testFrequentLyrics;


    	// create an artist object and invoke the function with the given input
    	$ArtistObject = new ArtistParser();
		$returnedJson = $ArtistObject->serializeObject($testArtist);

		$stubbedFrequentlyrics = "frequent-lyrics"; 

		// Create a stub for the SomeClass class.
		$stub = $this->createMock(trackParser::class);
		// Configure the stub.
		$stub->method('serializeObject')->willReturn($stubbedFrequentlyrics);

		$returnedName = $returnedJson["name"];
		$returnedIdentifier = $returnedJson["identifier"];
		$returnedImageURL = $returnedJson["imageURL"];
		$returnedTracks = $returnedJson["tracks"];
		$returnedFrequentLyrics = $returnedJson["frequentLyrics"];

    	// test that the artist representation is complete with the parsed data
		$this->assertEquals($returnedName, $testArtistName);
		$this->assertEquals($returnedIdentifier, $testArtistIdentifier);
		$this->assertEquals($returnedImageURL,$testArtistImageURL);
		$this->assertTrue(is_array($returnedFrequentLyrics));
		$this->assertTrue(is_array($testArtistTracks));
    }    
}

?>

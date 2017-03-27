<?php

class AuthorParserTest extends TestCase
{
	/**
     * Parses the JSON and returns an Author.
     *
     * @param array The key-value store of the representation of an Author.
     *
     * @return Author Returns an Author populated with data from $json.
     */
    public function testParseObject()
    {

	// 	$testInput = array(
	// 	     "name" => "foo-name",
	// 	     "small_image" => "http://small_image.html",
	// 	     "frequent_lyrics" => array(
	// 	     	"stringValue" => "Foo",
	// 	     	"frequency" => 10
	// 	     )
	//      );

	// // create an Author object and invoke the function with the given input
 //    $AuthorObject = new AuthorAParser();
	// $resultAuthor = $AuthorObject->parseObject($testInput);

 //    // fetch the properties from the Author
 //    $fetchedAuthorName = $resultAuthor->name;
 //    $fetchedAuthorImage = $resultAuthor->imageURL;
 //    $fetchedAuthorFrequency = $resultAuthor->frequentLyrics;

	// // test that the Author representation is complete with the parsed data
 //    $this->assertEquals($fetchedAuthorName, "foo-name");
 //    $this->assertEquals($fetchedAuthorImage, "http://small_image.html");
 //    $this->assertTrue(is_array($fetchedAuthorFrequency));
    }

    /**
     * Serializes an Author to JSON.
     *
     * @param Author $Author The Author to be serialized.
     *
     * @return array Returns the JSON representation of the Author.
     */
    function testSerializeObject()
    {
  //   	$testAuthorName = "foo-Author";
  //   	$testAuthorIdentifier = "foo-indentifier";
  //   	$testAuthorImageURL = "https://foo.com";
  //   	$testAuthorTracks = array("foo-track-1", "foo-track-2", "foo-track-3");
  //   	$testFrequentLyrics = array();

  //   	$testAuthor = new Author();
  //   	$testAuthor->identifier = $testAuthorIdentifier;
  //   	$testAuthor->name = $testAuthorName;
  //   	$testAuthor->imageURL = $testAuthorImageURL;
  //   	$testAuthor->frequentLyrics = $testFrequentLyrics;
  //   	$testAuthor->tracks = $testFrequentLyrics;


  //   	// create an Author object and invoke the function with the given input
  //   	$AuthorObject = new AuthorParser();
		// $returnedJson = $AuthorObject->serializeObject($testAuthor);

		// $stubbedFrequentlyrics = "frequent-lyrics"; 

		// // Create a stub for the SomeClass class.
		// // $stub = $this->createMock(TrackParser::class);
		// // Configure the stub.
		// // $stub->method('serializeObject')->willReturn($stubbedFrequentlyrics);

		// $returnedName = $returnedJson["name"];
		// $returnedIdentifier = $returnedJson["identifier"];
		// $returnedImageURL = $returnedJson["imageURL"];
		// $returnedTracks = $returnedJson["tracks"];
		// $returnedFrequentLyrics = $returnedJson["frequentLyrics"];

  //   	// test that the Author representation is complete with the parsed data
		// $this->assertEquals($returnedName, $testAuthorName);
		// $this->assertEquals($returnedIdentifier, $testAuthorIdentifier);
		// $this->assertEquals($returnedImageURL,$testAuthorImageURL);
		// $this->assertTrue(is_array($returnedFrequentLyrics));
		// $this->assertTrue(is_array($testAuthorTracks));
    }
}

?>

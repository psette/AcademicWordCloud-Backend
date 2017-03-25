<?php

use PHPUnit\Framework\TestCase;
use Http\Parsers\PaperParser as PaperParser;

class ACMPaperParserTest extends PHPUnit\Framework\TestCase
{
	/**
     * Parses the JSON and returns a Paper.
     *
     * @param array The key-value store of the representation of a Paper.
     *
     * @return Paper Returns an Paper populated with data from $json.
     */
    public function testParseObject()
    {
		$testInput = array(
		     "title" => "foo-name",
             "subtitle" => "bar-subtitle",
             "objectId" => "secret",
             "persons" => array(
                 0 => array(
                     "displayName" => "Author McName"
                 ),
                 1 => array(
                     "displayName" => "Boaty McBoatface"
                 )
             ),
             "tags" => array(
                 0 => array(
                     "tag" => "keyword"
                 ),
                 1 => array(
                     "tag" => "you're it"
                 )
             ),
             "attribs" => array(
                 0 => array(
                     "type" => "fulltext",
                     "format" => "txt",
                     "source" => "serebii.net"
                 ),
                 1 => array(
                     "type" => "fulltext",
                     "format" => "pdf",
                     "source" => "rileytestut.com"
                 )
             )
	     );

	// create an artist object and invoke the function with the given input
    $parser = new ACMPaperParser();
	$paper = $parser->parseObject($testInput);


	// test that the artist representation is complete with the parsed data
    $this->assertEquals($paper->title, "foo-name: bar-subtitle");
    $this->assertEquals($paper->identifier, "secret");
    $this->assertEquals($paper->pdf, "http://api.acm.org/dl/v1/download?type=fulltext&url=rileytestut.com");
    $this->assertTrue(is_array($paper->authors));
    $this->assertTrue(is_array($paper->keywords));
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
    	$testPaper = new Paper();
    	$testPaper->identifier = "secret";
    	$testPaper->name = "Hogwarts";
        $testPaper->pdf = "rileytestut.com";


    	// create an parser object and invoke the function with the given input
    	$parser = new ACMPaperParser();
		$returnedJson = $parser->serializeObject($testPaper);

		// Create a stub for the SomeClass class.
		// $stub = $this->createMock(TrackParser::class);
		// Configure the stub.
		// $stub->method('serializeObject')->willReturn($stubbedFrequentlyrics);

    	// test that the artist representation is complete with the parsed data
		$this->assertEquals($returnedJson["name"], $testPaper->title);
		$this->assertEquals($returnedJson["identifier"], $testPaper->identifier);

        // "PDF" should not be parsed.
        $this->assertFalse(array_key_exists("pdf", $returnedJson));
    }    
}

?>

<?php

class XMLPaperParserTest extends TestCase
{
	/*
	 * Test that parseBibtextLinkFromDownload returns the correct url concatenated
	 *	with the appropriate arn number supplied as a param
	 *
	 */
	public function testParseBibtextLinkFromDownload()
    {
        // $arnNumber = 10;
        // $expectedResponse = "http://ieeexplore.ieee.org/document/" . $arnNumber  . "/citations";

        // $xmlPaperParser = new XMLPaperParser();
        // $response = $xmlPaperParser->parseBibtextLinkFromDownload($arnNumber);

        // $this->assertEquals($response, $expectedResponse);
        $this->assertEquals(true, false);
    }

    /*
	 * Test that seralizeTitle returns the correct title serialized
	 * as json.
	 *
	 */
	public function testSerializeTitle()
    {
        // $title = "foo-title";
        // $serializedTitle = array("title" => $title);

        // $xmlPaperParser = new XMLPaperParser();
        // $response = $xmlPaperParser->serializeTitle($title);

        // $this->assertEquals($response, $serializedTitle);
        $this->assertEquals(true, false);
    }

    public function testSerializeObject()
    {
    	// $title = "foo-title";
     //    $serializedTitle = array("title" => $title);

     //    $testWord = new Word();
     //    $testWord->stringValue = "foo-string";
     //    $testWord->frequency = 10;


     //    $frequentWordsArray = array("expectedTitle" => $testWord);
     //    $wordParser = new WordParser();

     //    $paper = new Paper();
     //    $paper->title = "expectedTitle";
     //    $paper->identifier = "expectedIdentifier";
     //    $paper->bibtex = "expectedBibtex";
     //    $paper->download = "expectedDownload";
     //    $paper->pdf = "expectedPdf";
     //    $paper->pubYear = 2011;
     //    $paper->fullWords = "expectedFullWords";
     //    $paper->frequentWords = $frequentWordsArray;
     //    $paper->authors = "expectedAuthors";
     //    $paper->keywords = "expectedKeywords";
     //    $paper->abstract = "expectedAbstract";
     //    $paper->conference = "expectedConference";
     //    $paper->conferenceID = "expectedConferenceID";

     //    $expectedFrequentWords = array_map([$wordParser, "serializeObject"], $paper->frequentWords);

     //    $xmlPaperParser = new XMLPaperParser();
     //    $response = $xmlPaperParser->serializeObject($paper);

     //    $expectedJSON = [
     //        "title" => $paper->title,
     //        "identifier" => $paper->identifier,
     //        "bibtex" => $paper->bibtex,
     //        "download" => $paper->download,
     //        "pdf" => $paper->pdf,
     //        "pubYear" => $paper->pubYear,
     //        "fullWords" => $paper->fullWords,
     //        "frequentWords" => $expectedFrequentWords,
     //        "authors" => $paper->authors,
     //        "keywords" => $paper->keywords,
     //        "abstract" => $paper->abstract,
     //        "conference" => $paper->conference,
     //        "conferenceID" => $paper->conferenceID,
     //    ];

     //    $this->assertEquals($response, $expectedJSON);
        $this->assertEquals(true, false);
    }


}

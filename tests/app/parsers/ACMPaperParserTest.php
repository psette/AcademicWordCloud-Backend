<?php

class ACMPaperParserTest extends TestCase
{
	public function testFetchBibtex()
	{
        // $paper = new Paper();
        // $paper->identifier = 10;

        // $acmPaperParser = new ACMPaperParser();
        // $response = $acmPaperParser->fetchBibtex($paper);
        // $responseContainsEditor = strpos($response, "editor");
        // $responseContainsAuthor = strpos($response, "journal");
        // $responseContainsYear = strpos($response, "year");
        // $responseContainsIssn = strpos($response, "issn");
        // $responseContainsVolume = strpos($response, "volume");
        // $responseContainsNumber = strpos($response, "number");
        // $responseContainsIssueDate = strpos($response, "issue_date");
        // $responseContainsPublisher = strpos($response, "publisher");
        // $responseContainsAddress = strpos($response, "address");


        // $this->assertEquals(true, $responseContainsAuthor);
        // $this->assertEquals(true, $responseContainsEditor);
        // $this->assertEquals(true, $responseContainsYear);
        // $this->assertEquals(true, $responseContainsIssn);
        // $this->assertEquals(true, $responseContainsVolume);
        // $this->assertEquals(true, $responseContainsNumber);
        // $this->assertEquals(true, $responseContainsIssueDate);
        // $this->assertEquals(true, $responseContainsPublisher);
        // $this->assertEquals(true, $responseContainsAddress);

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
     //    $paper->fullWords = "expectedFullWords";
     //    $paper->frequentWords = $frequentWordsArray;
     //    $paper->pubYear = 2011;
     //    $paper->authors = "expectedAuthors";
     //    $paper->keywords = "expectedKeywords";
     //    $paper->abstract = "expectedAbstract";
     //    $paper->conference = "expectedConference";
     //    $paper->conferenceID = "expectedConferenceID";

     //    $expectedFrequentWords = array_map([$wordParser, "serializeObject"], $paper->frequentWords);

     //    $acmPaperParser = new ACMPaperParser();
     //    $response = $acmPaperParser->serializeObject($paper);

     //    $expectedJSON = [
     //        "title" => $paper->title,
     //        "bibtex" => $paper->bibtex,
     //        "download" => $paper->download,
     //        "pdf" => $paper->pdf,
     //        "pubYear" => $paper->pubYear,
     //        "fullWords" => $paper->fullWords,
     //        "frequentWords" => $expectedFrequentWords,
     //        "authors" => $paper->authors,
     //        "keywords" => $paper->keywords,
     //        "abstract" => $paper->abstract,
     //    ];

     //    $this->assertEquals($response, $expectedJSON);
        $this->assertEquals(true, false);
    }
}
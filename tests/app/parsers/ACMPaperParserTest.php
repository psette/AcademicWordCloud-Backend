<?php

class ACMPaperParserTest extends TestCase
{
	public function testParseBibtextLinkFromDownload()
	{
                $id = 10;
                $expectedResponse = "http://dl.acm.org/citation.cfm?id=" . $id . "&preflayout=flat";

                $acmPaperParser = new ACMPaperParser();
                $response = $acmPaperParser->parseBibtextLinkFromDownload($id);

                $this->assertEquals($response, $expectedResponse);
	}

	public function testSerializeObject()
            {
                $title = "foo-title";
                $serializedTitle = array("title" => $title);

                $testWord = new Word();
                $testWord->stringValue = "foo-string";
                $testWord->frequency = 10;


                $frequentWordsArray = array("expectedTitle" => $testWord);
                $wordParser = new WordParser();

                $paper = new Paper();
                $paper->title = "expectedTitle";
                $paper->identifier = "expectedIdentifier";
                $paper->bibtex = "expectedBibtex";
                $paper->download = "expectedDownload";
                $paper->pdf = "expectedPdf";
                $paper->pubYear = 2012;
                $paper->fullWords = "expectedFullWords";
                $paper->frequentWords = $frequentWordsArray;
                $paper->authors = "expectedAuthors";
                $paper->keywords = "expectedKeywords";
                $paper->abstract = "expectedAbstract";
                $paper->conference = "expectedConference";
                $paper->conferenceID = "expectedConferenceID";

                $expectedFrequentWords = array_map([$wordParser, "serializeObject"], $paper->frequentWords);

                $acmPaperParser = new ACMPaperParser();
                $response = $acmPaperParser->serializeObject($paper);

                $expectedJSON = [
                    "title" => $paper->title,
                    "bibtex" => $paper->bibtex,
                    "download" => $paper->download,
                    "pdf" => $paper->pdf,
                    "pubYear" => 2012,
                    "fullWords" => $paper->fullWords,
                    "frequentWords" => $expectedFrequentWords,
                    "authors" => $paper->authors,
                    "keywords" => $paper->keywords,
                    "abstract" => $paper->abstract,
                ];

                $this->assertEquals($response, $expectedJSON);
            }
}

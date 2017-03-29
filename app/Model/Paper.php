<?php

/**
 * A musical paper composed by an Author.
 */
class Paper
{
    /**
     * The title of the paper.
     *
     * @var string
     */
    var $title;

    /**
     * The unique identifier representing the paper.
     *
     * @var string
     */
    var $identifier;

    /**
     * The bibtex URL of the paper.
     *
     * @var string
     */
    var $bibtex;

    /**
     * The download URL of the paper.
     *
     * @var string
     */
    var $download;

    /**
     * The pdf URL of the paper.
     *
     * @var string
     */
    var $pdf;

    /**
     * The complete words for the paper.
     *
     * @var string
     */
    var $fullWords;

    /**
     * The papers's most frequently used words, sorted by frequency.
     *
     * @var array
     */
    var $frequentWords;

    /**
     * The authors that composed this paper.
     *
     * @var authors
     */
    var $authors;

    /**
     * The keywods of this paper.
     *
     * @var keywords
     */
    var $keywords;

      /**
     * The abstract of this paper.
     *
     * @var abstract
     */
    var $abstract;

      /**
     * The conference of this paper.
     * @var conference
     */
    var $conference;

    /**
     * The paper constructor.
     */
    function __construct()
    {
        $this->frequentWords = [];
        $this->authors = [];
        $this->keywords = [];

    }

    /**
     * Calculates the most frequent words from a set of papers.
     *
     * @param ModelSet $papers
     *
     * @return ModelSet.
     *
     */
    static function frequentWordsFromPapers($papers)
    {
        // $words = [];

        // if (is_null($papers))
        // {
        //     return $words;
        // }

        // foreach ($papers as $paper)
        // {
        //     foreach ($paper->frequentwords as $frequentLyric)
        //     {
        //         $lyric = null;

        //         if (array_key_exists($frequentLyric->stringValue, $words))
        //         {
        //             $lyric = $words[$frequentLyric->stringValue];
        //         }
        //         else
        //         {
        //             $lyric = new Lyric();
        //             $lyric->stringValue = $frequentLyric->stringValue;
        //             $lyric->identifier = $frequentLyric->stringValue;
        //         }

        //         $lyric->frequency = $lyric->frequency + $frequentLyric->frequency;
        //         $lyric->papers->attach($paper);

        //         $words[$frequentLyric->stringValue] = $lyric;
        //     }
        // }

        // usort($words, ["Lyric", "compareByFrequency"]);

        // return $words;
    }
}

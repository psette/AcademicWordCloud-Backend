<?php

/**
 * A musical track composed by an artist.
 */
class Track
{
    /**
     * The full Lyrics
     *
     * @var string
     */
    var $fullLyrics;
    /**
     * The name of the Track.
     *
     * @var string
     */
    var $name;

    /**
     * The unique identifier representing the Track.
     *
     * @var string
     */
    var $identifier;

    /**
     * The metadata URL of the Track.
     *
     * @var string
     */
    var $url;

    /**
     * The Tracks's most frequently used Lyrics, sorted by frequency.
     *
     * @var array
     */
    var $frequentLyrics;

    /**
     * The Artist that composed this Track.
     *
     * @var Artist
     */
    var $artist;

    function __construct()
    {
        $this->frequentLyrics = [];
    }

    static function frequentLyricsFromTracks($tracks)
    {
        $lyrics = [];

        if (is_null($tracks))
        {
            return $lyrics;
        }

        foreach ($tracks as $track)
        {
            foreach ($track->frequentLyrics as $frequentLyric)
            {
                $lyric = null;

                if (array_key_exists($frequentLyric->stringValue, $lyrics))
                {
                    $lyric = $lyrics[$frequentLyric->stringValue];
                }
                else
                {
                    $lyric = new Lyric();
                    $lyric->stringValue = $frequentLyric->stringValue;
                    $lyric->identifier = $frequentLyric->stringValue;
                }

                $lyric->frequency = $lyric->frequency + $frequentLyric->frequency;
                $lyric->tracks->attach($track);

                $lyrics[$frequentLyric->stringValue] = $lyric;
            }
        }

        usort($lyrics, ["Lyric", "compareByFrequency"]);

        return $lyrics;
    }
}

<?php
  private function fetchTrack($url, $artist)
    {
        $file = @file_get_contents($url);
        if ($file == FALSE)
        {
            return null;
        }

        $json = json_decode($file, true);

        // If no result key, there was an error so return null.
        if (!array_key_exists("result", $json))
        {
            return null;
        }

        $trackJSON = $json["result"];

        $trackParser = new TrackParser();
        $trackParser->artist = $artist;

        $track = $trackParser->parseObject($trackJSON);

        return $track;
    }
class fetchTrackTest extends TestCase
{
    /**
     * Parses the JSON and returns an Artist.
     *
     * @param array The key-value store of the representation of an Artist.
     *
     * @return Artist Returns an Artist populated with data from $json.
     */
    public function testFetchTrack()
    {

        $fetchedTrack = fetchedTrack();
    }
}

?>

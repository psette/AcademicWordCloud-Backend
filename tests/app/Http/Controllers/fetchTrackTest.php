<?php
use App\Http\Controllers\Server as Server;
use App\Http\Controllers\ServerHelper as ServerHelper;

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
        $blankArray = array();
        $json = json_encode(array(
            "result" => array(
                "name" => "foo-name",
                "url" => "foo-url",
                "small_image" => "foo-sImage-url",
                "medium_image" => "foo-mImage-url",
                "large_image" => "foo-LImage-url",
                "itunes" => "foo-itunes-url",
                "albums" => $blankArray,
                "songs" =>  $blankArray
              )
        ));

        $track = new Track();
        $track->name= "foo-song-name";
        $track->url =  "foo-song-url" ;
        $stub = $this->createMock(ServerHelper::class);
        $stub->method('getURLsafe')->willReturn($json);

        $server = new Server();
        $server->setHelper($stub);

        $fetchedTrack =  $server->fetchTrack("URL" , "ARTIST");


        $this->assertEquals($fetchedTrack->name, "foo-name");
        $this->assertEquals($fetchedTrack->url, "foo-url");
        $this->assertEquals($fetchedTrack->artist, "ARTIST");
        $this->assertNull($fetchedTrack->lyrics);
        $this->assertTrue(is_array($fetchedTrack->frequentLyrics));
        $this->assertNull($fetchedTrack->fullLyrics);

    }
};
?>

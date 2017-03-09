<?php
/*
@covers \App\Http\Controllers\Server

*/
class ServerTest extends TestCase
{
	/*
     * Test that Server::SearchArtist retrieves the file contents from the given artist
     * Input: $dummyRequest - Illuminate\Http\Request Object
     *        $testArtist - String of artist name you would like to search
     * Description: This function is testing that the searchArtist() function retrieves the
     * string of the corresponding artist search by invoking he method getArtistPageString()
     * with the arguments of the artist
     *
     */
	public function testSearchArtistRetrievesFileContents()
    {
    	$dummyRequest = new Illuminate\Http\Request();
        $testArtist = "the+killers";

        // Create a stub for the Server class.
        $serverStub = $this->createMock(App\Http\Controllers\Server::class);
        $serverStub = $this->getMockBuilder(App\Http\Controllers\Server::class)
                         ->setMethods(['getArtistPageString'])
                         ->getMock();

        // ensure that it gets the file contents from the given artist
        $serverStub->expects($this->once())
                 ->method('getArtistPageString')
                 ->with($this->equalTo($testArtist));
        // invoke the function
       $serverStub->searchArtists($dummyRequest, $testArtist);

    }

    /*
     * Test that Server::SearchArtist returns an empty array if no lyrics are found
     * Input: $dummyRequest - Illuminate\Http\Request Object
     *        $testArtist - String of artist name you would like to search
     * Description: This function is testing that the searchArtist() function returns
     *              an empty array, as expected by the client, if there is no array with the
     *              key of "result". This may occur if the api receives a search string that
     *              it does not have any results for
     *
     *
     */
    public function testEmptyArrayReturned()
    {
        $dummyRequest = new Illuminate\Http\Request();
        $testArtist = "foo+bar";

        // Create a stub for the Server class.
        $serverStub = $this->createMock(App\Http\Controllers\Server::class);
        $serverStub = $this->getMockBuilder(App\Http\Controllers\Server::class)
                         ->setMethods(['getArtistPageString'])
                         ->getMock();

        // ensure that it gets the file contents from the given artist
        // Configure the stub.
        $serverStub->method('getArtistPageString')
             ->willReturn(false);
        // invoke the function
       $response = $serverStub->searchArtists($dummyRequest, $testArtist);

       $this->assertEquals($response, array());
    }

    /*
     * Test that Server::SearchArtist returns an empty array if no lyrics are found
     * Input: $dummyRequest - Illuminate\Http\Request Object
     *        $testArtist - String of artist name you would like to search
     * Description: This function is testing that the searchArtist() function returns
     *              an empty array, as expected by the client, if there is no array with the
     *              key of "songs" within the array of "results. This may occur if the api receives a search string that
     *              it does not have any lyrics for
     *
     *
     */
    public function testNoSongsFound()
    {
        $jsonReponse = json_encode(array(
            "foo" => "bar"
        ));

        $dummyRequest = new Illuminate\Http\Request();
        $testArtist = "the+killers";

        // Create a stub for the Server class.
        $serverStub = $this->createMock(App\Http\Controllers\Server::class);
        $serverStub = $this->getMockBuilder(App\Http\Controllers\Server::class)
                         ->setMethods(['getArtistPageString'])
                         ->getMock();

        // ensure that it gets the file contents from the given artist
        // Configure the stub.
        $serverStub->method('getArtistPageString')
             ->willReturn($jsonReponse);
        // invoke the function
       $response = $serverStub->searchArtists($dummyRequest, $testArtist);

       $decodedResponse = json_decode($response);

       $this->assertEquals($decodedResponse, array());
    }


    /*
     * Test that Server::SearchArtist returns an empty array if no lyrics are found
     * Input: $dummyRequest - Illuminate\Http\Request Object
     *        $testArtist - String of artist name you would like to search
     * Description: This function is testing that the searchArtist() function returns
     *              the json response in the format that the client expects to consume it in.
     *              This includes ensuring that the lyrics are nested properly within the "result"
     *              array with the corresponding lyrics to songs listed in an array under the key "songs"
     *              which then contains the corresponding lyrics
     *              This sample response was created by constructing the response given the barebones JSON structure
     *              expected, and then populating that array with lyrics that were copy and pasted from an online search.
     */
    public function testValidSongsResponse()
    {
        /*
         * Load an expected response that we define as expected
         */
        $jsonResponse = file_get_contents("SampleResponse.json", true);
        $expectedResponse = json_decode(file_get_contents("ExpectedBody.json", true));
        $dummyRequest = new Illuminate\Http\Request();
        $testArtist = "the+killers";

        // Create a stub for the Server class.
        $serverStub = $this->createMock(App\Http\Controllers\Server::class);
        $serverStub = $this->getMockBuilder(App\Http\Controllers\Server::class)
                         ->setMethods(['getArtistPageString'])
                         ->getMock();

        // return the sample JSON response when the search is made for it
        $serverStub->method('getArtistPageString')
             ->willReturn($jsonResponse);

        // invoke the function and retrieve the response
       $response = $serverStub->searchArtists($dummyRequest, $testArtist);
       $decodedContent = json_decode($response->content());

       // assert that the response matches the calculated expected response from the given crafted input
       $this->assertEquals($expectedResponse, $decodedContent);
       $this->assertEquals(200, $response->status());
    }
}

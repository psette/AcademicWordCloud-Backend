<?php

class ServerTest extends TestCase
{
	/*
     * Test that Server::SearchArtist retrieves the file contents from the given artist
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
     * Test that an empty array is returned if the file contents cannot be read
     * 
     */
    public function testEmptyArrayReturned()
    {
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
             ->willReturn(false);
        // invoke the function
       $response = $serverStub->searchArtists($dummyRequest, $testArtist);

       $this->assertEquals($response, array());
    }

    /*
     * Test that an empty response is returned if the results key is not found
     * 
     */
    public function testNoResultsFound()
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
     * Test a valid json response that has the songs keyword
     * against the calculatd JSON response
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
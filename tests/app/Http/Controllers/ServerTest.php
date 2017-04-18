<?php // @covers \App\Http\Controllers\Server

class ServerTest extends TestCase
{
    /*
     * Test IEEE file retrieval when the author CAN be fournd
     */
    public function testGetIEEEFile()
    {
       //  $testAuthor = "sette";
       //  $searchType = "name";

       //  $server = new App\Http\Controllers\Server();

       //  // invoke the function
       // $response = $server->get_IEEE_file($searchType, $testAuthor);
       // $this->assertInstanceOf(SimpleXMLElement::class, $response);
        $this->assertEquals(true, false);
    }

    /*
     * Test IEEE file retrieval when the author CANNOT be fournd
     */
    public function testGetIEEEFileNotFound()
    {
       //  $testAuthor = "foooooooo";
       //  $searchType = "name";
       //  $notFoundResponse = "FILE IS NOT FOUND";

       //  // Create a stub for the Server class.
       //  $serverStub = $this->createMock(App\Http\Controllers\Server::class);

       //  $serverStub = $this->getMockBuilder(App\Http\Controllers\Server::class)
       //                   ->setMethods(['get_IEEE_file'])
       //                   ->getMock();

       //  $server = new App\Http\Controllers\Server();

       //  // invoke the function
       // $response = $server->get_IEEE_file($searchType, $testAuthor);
       // $this->assertEquals($response, $notFoundResponse);
        $this->assertEquals(true, false);
    }

    /*
     * Test the parseXMLObject()
     */
    public function testParseXMLObject()
    {

        // $testFile = new Paper();
        // $papersToSearch = 2;

        // $testAuthor = "foo+author";
        // // Create a stub for the Server class.
        // // Create a stub for the Server class.
        // $serverStub = $this->createMock(App\Http\Controllers\Server::class);

        // $serverStub = $this->getMockBuilder(App\Http\Controllers\Server::class)
        //                  ->setMethods(['parseXMLObject'])
        //                  ->getMock();

        // $testArray = array();
        // /// invoke the function
        // $response = $serverStub->parseXMLObject($testFile, $papersToSearch);

        // $this->assertEquals($response, NULL);

        $this->assertEquals(true, false);
    }

    /*
     *
     */
    public function testSearchAuthors()
    {
       //  /*
       //   * Load an expected response that we define as expected
       //   */
       //  $jsonResponse = file_get_contents("SampleResponse.json", true);
       //  $expectedResponse = json_decode(file_get_contents("ExpectedBody.json", true));
       //  $dummyRequest = new Illuminate\Http\Request();
       //  $testAuthor = "sette";

       //  // Create a stub for the Server class.
       //  $serverStub = $this->createMock(App\Http\Controllers\Server::class);
       //  $serverStub = $this->getMockBuilder(App\Http\Controllers\Server::class)
       //                   ->setMethods(['searchAuthors'])
       //                   ->getMock();

       //  // invoke the function and retrieve the response
       // $response = $serverStub->searchAuthors($dummyRequest, $testAuthor);
       // $decodedContent = json_decode($response);
       // // assert that the response matches the calculated expected response from the given crafted input
       // $this->assertTrue(is_string($testAuthor));

        $this->assertEquals(true, false);
    }

    public function testGetProgress()
    {
        // $_SESSION = array('maximumPaperCount' => 0);

        // $dummyRequest = new Illuminate\Http\Request();
        //  // Create a stub for the Server class.
        // $serverStub = $this->createMock(App\Http\Controllers\Server::class);
        // $serverStub = $this->getMockBuilder(App\Http\Controllers\Server::class)
        //                  ->setMethods(['getProgress'])
        //                  ->getMock();

        // $response = $serverStub->getProgress($dummyRequest);
        // $this->assertEquals($response, 0);
        $this->assertEquals(true, false);
    }
}

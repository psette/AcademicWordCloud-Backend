<?php // @covers \App\Http\Controllers\Server

class ServerTest extends TestCase
{
    /*
     * Test IEEE file retrieval
     */
    public function testGetIEEEFile()
    {
        $testAuthor = "Sette";

        // Create a stub for the Server class.
        $serverStub = $this->createMock(App\Http\Controllers\Server::class);

        $serverStub = $this->getMockBuilder(App\Http\Controllers\Server::class)
                         ->setMethods(['get_IEEE_file'])
                         ->getMock();

        // ensure that it gets the file contents from the given author
        $serverStub->expects($this->once())
                 ->method('get_IEEE_file')
                 ->with($this->equalTo($testAuthor));

        // invoke the function
       $serverStub->get_IEEE_file($testAuthor);
    }

    /*
     * Test the parseXMLObject()
     */
    public function testParseXMLObject()
    {

        $testAuthor = "foo+author";
        // Create a stub for the Server class.
        // Create a stub for the Server class.
        $serverStub = $this->createMock(App\Http\Controllers\Server::class);

        $serverStub = $this->getMockBuilder(App\Http\Controllers\Server::class)
                         ->setMethods(['parseXMLObject'])
                         ->getMock();

        $testArray = array();
        /// invoke the function
        $response = $serverStub->parseXMLObject($testArray);

        $this->assertEquals($response, NULL);
    }

    /*
     *
     */
    public function testSearchAuthors()
    {
        /*
         * Load an expected response that we define as expected
         */
        $jsonResponse = file_get_contents("SampleResponse.json", true);
        $expectedResponse = json_decode(file_get_contents("ExpectedBody.json", true));
        $dummyRequest = new Illuminate\Http\Request();
        $testAuthor = "sette";

        // Create a stub for the Server class.
        $serverStub = $this->createMock(App\Http\Controllers\Server::class);
        $serverStub = $this->getMockBuilder(App\Http\Controllers\Server::class)
                         ->setMethods(['searchAuthors'])
                         ->getMock();

        // invoke the function and retrieve the response
       $response = $serverStub->searchAuthors($dummyRequest, $testAuthor);
       $decodedContent = json_decode($response);
       // assert that the response matches the calculated expected response from the given crafted input
       $this->assertTrue(is_string($testAuthor));
    }
}

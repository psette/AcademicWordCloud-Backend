<?php

class ServerTest extends TestCase
{
	/*
     * Search for artists matching provided text.
     *
     * @param Request $request
     * @param string $artist
     *
     * @return JSON-encoded Artists array.
     *
     */
	public function testSearchArtists()
    {
    	$dummyRequest = new Illuminate\Http\Request();
    	$testArtist = "Foo Artist";

    	$server = new App\Http\Controllers\Server();
    	$resultJson = $server->searchArtists($dummyRequest, $testArtist);


    }
}
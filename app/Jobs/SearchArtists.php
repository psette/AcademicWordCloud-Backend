<?php

namespace App\Jobs;

use App\Jobs\Job;
class SearchArtists extends Job
{

        protected  $artist;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($artist)
    {
        $this->artist = $artist;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
       $location = "http://lyrics.wikia.com/wikia.php?controller=LyricsApi&method=searchArtist&query=" . urlencode($artist);
        $file = file_get_contents($location);
        $json = json_decode($file, true);
        $artists[] =  new \Artist();
        $parser = new \ArtistParser();

        if( !array_key_exists("result", $json)){
            echo "No Artist found under the name $artist \n";
            return;
        }

        foreach($json['result'] as $artistsArray) {
            $artist = $parser->parseObject($artistsArray);
            array_push($artists, $artist);

            if( array_key_exists( "songs",   $artistsArray) ){
                $this->fetchTracks($artist, $artistsArray);
            }

        }

        return;
    }
}

<?php

namespace App\Jobs;

use App\Jobs\Job;
class FetchTracks extends Job
{

        // artist whose tracks we are going to fetch
        protected  $artist;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($artist)
    {
        $this->$artist = $artist;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $tracks[] =  new \Track();
        $parser = new \TrackParser();

        foreach( $artistsArray['songs'] as $trackItem ) {
            if(!array_key_exists("url", $trackItem))
            {
                echo "URL Does not exist for " . $trackItem['name'];
                continue;
            }

            $track = $parser->parseObject($trackItem);
            $track->artist = $artist;
            array_push($tracks, $track);
            $this->fetchLyrics($track);
        }

        return;
    }
}

<?php

namespace App\Jobs;

use App\Jobs\Job;
class FetchLyrics extends Job
{

        protected  $track;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($trackURL, $artist)
    {
        $this->$trackURL = $trackURL;
        $this->$artist = $artist;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $location = $this->$trackURL;
        $file = @file_get_contents(html_entity_decode($trackURL));

        if($file == FALSE )
        {
            echo " File does not exsist for   \n"  .  $trackURL ;
            return;
        }

       $json = json_decode($file, true);
        if (!array_key_exists("result", $json))
        {
            return null;
        }

        $trackJSON = $json["result"];
        $trackParser = new TrackParser();
        $trackParser->$artist = $this->$artist;
        $track = $trackParser->parseObject($trackJSON);
        return $track;
    }
}

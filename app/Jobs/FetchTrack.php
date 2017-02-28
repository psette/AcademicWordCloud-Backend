<?php

namespace App\Jobs;

use App\Jobs\Job;
class FetchTrack extends Job
{

        // artist whose tracks we are going to fetch
        protected  $artist;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($trackURL, $artist)
    {
        $this->$trackURL = $trackURL;
        $this->$artist = $artist;

        echo "Creating a track job";
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        echo "Handling a track job";

        $file = @file_get_contents(html_entity_decode($trackURL));
        if ($file == FALSE)
        {
            return null;
        }
        $json = json_decode($file, true);
        if (!array_key_exists("result", $json))
        {
            return null;
        }
        $trackJSON = $json["result"];
        $trackParser = new TrackParser();
        $trackParser->artist = $this->$artist;
        $track = $trackParser->parseObject($trackJSON);
        return $track;
    }
}

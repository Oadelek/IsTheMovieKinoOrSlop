<?php
class OMDB {
    private $apiKey = $_ENV['OMDB_KEY'];

    public function getMovieDetails($title) {
        $url = "http://www.omdbapi.com/?t=" . urlencode($title) . "&apikey=" . $this->apiKey;
        $response = file_get_contents($url);
        return json_decode($response, true);
    }
}

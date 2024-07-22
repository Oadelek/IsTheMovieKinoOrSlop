<?php
class OMDB {
    private $apiKey;
    private $baseUrl = 'http://www.omdbapi.com/';

    public function __construct() {
        $this->apiKey = $_ENV['OMDB_KEY'];
    }

    public function getMovieDetails($title) {
        $query = [
            't' => $title,
            'apikey' => $this->apiKey
        ];
        return $this->makeRequest($query);
    }

    private function makeRequest($query) {
        $url = $this->baseUrl . '?' . http_build_query($query);

        $options = [
            'http' => [
                'method'  => 'GET',
                'timeout' => 10 // Timeout in seconds
            ]
        ];

        $context = stream_context_create($options);
        $response = @file_get_contents($url, false, $context);

        if ($response === FALSE) {
            return ['error' => 'Unable to fetch data from OMDB API.'];
        }

        $data = json_decode($response, true);

        if (isset($data['Error'])) {
            return ['error' => $data['Error']];
        }

        return $data;
    }
}
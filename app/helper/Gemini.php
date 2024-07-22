<?php

class Gemini {
    private $apiKey;
    private $baseUrl = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent';

    public function __construct() {
        $this->apiKey = $_ENV['GEMINI_API_KEY'];
    }

    public function generateReview($rating, $filters) {
        $prompt = $this->createPrompt($rating, $filters);

        $data = [
            'contents' => [
                ['parts' => [['text' => $prompt]]]
            ]
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->baseUrl . '?key=' . $this->apiKey);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json'
        ]);

        $response = curl_exec($ch);
        curl_close($ch);

        $result = json_decode($response, true);

        if (isset($result['candidates'][0]['content']['parts'][0]['text'])) {
            return $result['candidates'][0]['content']['parts'][0]['text'];
        } else {
            return 'Unable to generate content. Please try again.';
        }
    }

    private function createPrompt($rating, $filters) {
        $prompt = "Generate a movie review with a rating of $rating. ";

        if (isset($filters['word_count'])) {
            $prompt .= "The review should be around " . $filters['word_count'] . " words. ";
        }
        if (isset($filters['humor_level'])) {
            $prompt .= "The humor level should be " . $filters['humor_level'] . "/10. ";
        }
        if (isset($filters['critic_level'])) {
            $prompt .= "The critic level should be " . $filters['critic_level'] . "/10. ";
        }
        if (isset($filters['style'])) {
            $prompt .= "The style should be " . $filters['style'] . ". ";
        }

        return $prompt;
    }
}

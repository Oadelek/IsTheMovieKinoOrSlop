<?php
class Gemini {
    private $apiKey;
    private $baseUrl;

    public function __construct() {
        $this->apiKey = getenv('GOOGLE_API_KEY');
        $this->baseUrl = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-pro:generateContent?key=' . $this->apiKey;
    }

    public function generateReview($rating, $movieTitle, $filters) {
        $prompt = $this->createPrompt($rating, $movieTitle, $filters);
        $data = [
            'contents' => [
                [
                    'parts' => [
                        [
                            'text' => $prompt
                        ]
                    ]
                ]
            ]
        ];

        $options = [
            'http' => [
                'method' => 'POST',
                'header' => 'Content-Type: application/json',
                'content' => json_encode($data)
            ]
        ];

        $context = stream_context_create($options);
        $result = file_get_contents($this->baseUrl, false, $context);

        if ($result === FALSE) {
            error_log("Error fetching content from Gemini API");
            return 'Unable to generate content. Please try again.';
        }

        $response = json_decode($result, true);

        // Debugging: Log the API response (remove or comment out this line in production)
        error_log('API Response: ' . print_r($response, true));

        // Check for errors and return an appropriate message
        if (isset($response['error'])) {
            return "Error generating content: " . $response['error']['message'];
        }
        if (isset($response['candidates'][0]['content']['parts'][0]['text'])) {
            return $response['candidates'][0]['content']['parts'][0]['text'];
        } else {
            return 'Unable to generate content. Please try again.';
        }
    }

    private function createPrompt($rating, $movieTitle, $filters) {
        $prompt = "Generate a movie review for the movie titled \"" . addslashes($movieTitle) . "\" with a rating of $rating/10. ";
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
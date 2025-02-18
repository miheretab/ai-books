<?php

require './vendor/autoload.php';

include_once 'Book.php';

use GeminiAPI\Client;
use GeminiAPI\Resources\ModelName;
use GeminiAPI\Resources\Parts\TextPart;

class AI {
    
    static function getSummary(Book $book) {
        if (!$book) {
            return ['error' => 'no book'];
        }

        $apiKey = getenv("GEMINI_API_KEY");
        $query = 'Summarize ' . $book->getTitle() . ' Book by ' . $book->getAuthor() . ' published at ' . $book->getPublishedYear();

        try {

            $client = new Client($apiKey);
            $response = $client->generativeModel(ModelName::GEMINI_PRO)->generateContent(
                new TextPart($query),
            );

            $summary = $response->text();

            return $summary;
        } catch (Exception $ex) {
            return ['error' => $ex->getMessage()];
        }
    }
}

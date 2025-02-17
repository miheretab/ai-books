<?php

include_once 'Book.php';

Class AI {
    
    static function getSummary(Book $book, $url = "https://api.deepseek.com/chat/completions", $apiKey = "sk-1cd9ea00e1b74f1ca02c83d9769fc06e") {
        
        $postVar = [
            "model" =>  "deepseek-chat",
            "messages" => [
                ["role" => "user", "content" => 'Summarize ' . $book->getTitle() . ' Book by ' . $book->getAuthor() . ' published at ' . $book->getPublishedYear()]
            ],
            "stream" => false
        ];

        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer $apiKey",
            "Content-Type: application/json"
        ]);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postVar));

        $data = curl_exec($ch);
        $curl_errno = curl_errno($ch);
        $curl_error = curl_error($ch);

        curl_close($ch);
        return $data;
    }
}

/*curl https://api.deepseek.com/chat/completions \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer sk-1cd9ea00e1b74f1ca02c83d9769fc06e" \
  -d '{
        "model": "deepseek-chat",
        "messages": [
          {"role": "system", "content": "You are a helpful assistant."},
          {"role": "user", "content": "Hello!"}
        ],
        "stream": false
      }'*/
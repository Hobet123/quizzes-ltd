<?php

$apiKey = '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'; // Replace with your OpenAI API key
$url = 'https://api.openai.com/v1/engines/davinci-codex/completions';
//https://api.openai.com/v1/completions

$data = [
    'prompt' => 'tell me more about York University please',
    'max_tokens' => 100,
];

$options = [
    'http' => [
        'header' => "Content-Type: application/json\r\n",
        'header' => "Authorization: Bearer $apiKey\r\n",
        'method' => 'POST',
        'content' => json_encode($data),
    ],
];

$context = stream_context_create($options);
$result = file_get_contents($url, false, $context);

if ($result === false) {
    echo "Failed to make the request.";
} else {
    $response = json_decode($result, true);
    echo $response['choices'][0]['text'];
}
?>

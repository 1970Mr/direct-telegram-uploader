<?php

namespace DirectFileUploader;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class TelegramClient
{
    private $client;
    private $token;
    private $groupId;

    public function __construct(string $token, string $groupId)
    {
        $this->client = new Client();
        $this->token = $token;
        $this->groupId = $groupId;
    }

    public function sendFile(string $filePath): bool
    {
        try {
            $response = $this->client->request('POST', 'https://api.telegram.org/bot' . $this->token . '/sendDocument', [
                'multipart' => [
                    [
                        'name' => 'chat_id',
                        'contents' => $this->groupId,
                    ],
                    [
                        'name' => 'document',
                        'contents' => fopen($filePath, 'r'),
                    ],
                ],
            ]);

            return true;
        } catch (RequestException $e) {
            echo "An error occurred while sending the file '{$filePath}': " . $e->getMessage() . PHP_EOL;
            return false;
        }
    }
}

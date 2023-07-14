<?php

require 'vendor/autoload.php';

use DirectFileUploader\TelegramClient;
use DirectFileUploader\Uploader;
use Dotenv\Dotenv;

// Load environment variables from .env file
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Telegram API token
$token = $_ENV['TELEGRAM_API_TOKEN'];
// Telegram group ID
$groupId = $_ENV['TELEGRAM_GROUP_ID'];

// Create Telegram client
$telegramClient = new TelegramClient($token, $groupId);

// Create Uploader
$uploader = new Uploader($telegramClient);

// Folder path from command-line argument
if ($argc < 2) {
    echo "Usage: php send_files.php <folder_path>" . PHP_EOL;
    exit(1);
}
$folderPath = $argv[1];

// Send files to the group
$uploader->sendFilesToGroup($folderPath);

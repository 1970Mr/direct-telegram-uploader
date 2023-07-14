<?php
require 'vendor/autoload.php';

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Dotenv\Dotenv;

// Load environment variables from .env file
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Telegram API token
$token = $_ENV['TELEGRAM_API_TOKEN'];
// Telegram group ID
$groupId = $_ENV['TELEGRAM_GROUP_ID'];

$client = new Client();

function send_file_to_group($file_path, $token, $groupId, $client) {
    try {
        $response = $client->request('POST', 'https://api.telegram.org/bot' . $token . '/sendDocument', [
            'multipart' => [
                [
                    'name' => 'chat_id',
                    'contents' => $groupId
                ],
                [
                    'name' => 'document',
                    'contents' => fopen($file_path, 'r')
                ]
            ]
        ]);

        return true;
    } catch (RequestException $e) {
        echo "An error occurred while sending the file '{$file_path}': " . $e->getMessage() . PHP_EOL;
        return false;
    }
}

function display_success_message($file_path) {
    echo PHP_EOL;
    echo "File '{$file_path}' sent successfully to the group!" . PHP_EOL;
}

function format_size($size) {
    $labels = ['B', 'KB', 'MB', 'GB', 'TB'];
    $power = 1024;
    $n = 0;

    while ($size > $power) {
        $size /= $power;
        $n++;
    }

    return floor($size) . ' ' . $labels[$n];
}

function format_progress($current, $total) {
    return $current . '/' . $total;
}

function send_files_to_group($folder_path, $token, $groupId, $client) {
    $success_count = 0;
    $total_files = 0;
    $total_size = 0;

    $files = scandir($folder_path);
    foreach ($files as $file) {
        $file_path = $folder_path . '/' . $file;
        if (is_file($file_path)) {
            $total_files++;
            $total_size += filesize($file_path);
        }
    }

    $progress_bar = new \Symfony\Component\Console\Helper\ProgressBar(new \Symfony\Component\Console\Output\ConsoleOutput(), $total_files);
    $progress_bar->setFormat("Uploading files: [%bar%] %percent:3s%% (%current%/%max%) [%uploaded%/%total_size%]");
    $progress_bar->start();

    foreach ($files as $file) {
        $file_path = $folder_path . '/' . $file;
        if (is_file($file_path)) {
            $success = send_file_to_group($file_path, $token, $groupId, $client);
            if ($success) {
                display_success_message($file_path);
                $success_count++;
            }

            $progress_bar->setProgress($success_count);
            $uploaded_size = 0;
            for ($i = 1; $i <= $success_count; $i++) {
                $uploaded_file = $folder_path . '/' . $files[$i];
                $uploaded_size += filesize($uploaded_file);
            }
            $progress_bar->setMessage(format_progress($success_count, $total_files), 'current');
            $progress_bar->setMessage(format_size($uploaded_size), 'uploaded');
            $progress_bar->setMessage(format_size($total_size), 'total_size');
        }
    }

    $progress_bar->finish();
    echo PHP_EOL;
    echo "Successfully sent {$success_count} files to the group." . PHP_EOL;
    echo PHP_EOL;
}

// Example usage
if ($argc < 2) {
    echo "Usage: php send_file_to_telegram.php <folder_path>" . PHP_EOL;
    exit(1);
}

$folder_path = $argv[1];

send_files_to_group($folder_path, $token, $groupId, $client);

// "Telegram\\Uploader\\": "src/"


<?php

namespace DirectFileUploader;

class Uploader
{
    private $telegramClient;

    public function __construct(TelegramClient $telegramClient)
    {
        $this->telegramClient = $telegramClient;
    }

    public function sendFilesToGroup(string $folderPath): void
    {
        $successCount = 0;
        $totalFiles = 0;
        $totalSize = 0;

        $files = scandir($folderPath);
        foreach ($files as $file) {
            $filePath = $folderPath . '/' . $file;
            if (is_file($filePath)) {
                $totalFiles++;
                $totalSize += filesize($filePath);
            }
        }

        $progressBar = new ProgressBar($totalFiles);

        foreach ($files as $file) {
            $filePath = $folderPath . '/' . $file;
            if (is_file($filePath)) {
                $success = $this->telegramClient->sendFile($filePath);
                if ($success) {
                    echo PHP_EOL;
                    echo "File '{$filePath}' sent successfully to the group!" . PHP_EOL;
                    $successCount++;
                }

                $progressBar->setProgress($successCount);
                $uploadedSize = 0;
                for ($i = 1; $i <= $successCount; $i++) {
                    $uploadedFile = $folderPath . '/' . $files[$i];
                    $uploadedSize += filesize($uploadedFile);
                }
                $progressBar->setMessage(format_progress($successCount, $totalFiles), 'current');
                $progressBar->setMessage(format_size($uploadedSize), 'uploaded');
                $progressBar->setMessage(format_size($totalSize), 'total_size');
            }
        }

        $progressBar->finish();
        echo PHP_EOL;
        echo "Successfully sent {$successCount} files to the group." . PHP_EOL;
        echo PHP_EOL;
    }
}

function format_progress($current, $total)
{
    return $current . '/' . $total;
}

function format_size($size)
{
    $labels = ['B', 'KB', 'MB', 'GB', 'TB'];
    $power = 1024;
    $n = 0;

    while ($size > $power) {
        $size /= $power;
        $n++;
    }

    return floor($size) . ' ' . $labels[$n];
}

<?php

namespace DirectFileUploader;

use Symfony\Component\Console\Helper\ProgressBar as SymfonyProgressBar;
use Symfony\Component\Console\Output\ConsoleOutput;

class ProgressBar
{
    private $progressBar;

    public function __construct(int $totalItems)
    {
        $this->progressBar = new SymfonyProgressBar(new ConsoleOutput(), $totalItems);
        $this->progressBar->setFormat("Uploading files: [%bar%] %percent:3s%% (%current%/%max%) [%uploaded%/%total_size%]");
        $this->progressBar->start();
    }

    public function setProgress(int $value): void
    {
        $this->progressBar->setProgress($value);
    }

    public function setMessage(string $message, string $key): void
    {
        $this->progressBar->setMessage($message, $key);
    }

    public function finish(): void
    {
        $this->progressBar->finish();
        echo PHP_EOL;
    }
}

<?php

namespace Laravel\BrowserKitTesting\Tests\Stubs;

use Symfony\Component\Console\Formatter\OutputFormatterInterface;
use Symfony\Component\Console\Output\OutputInterface;

class OutputStub implements OutputInterface
{
    public function write($messages, $newline = false, $options = 0)
    {
    }

    public function writeln($messages, $options = 0)
    {
    }

    public function setVerbosity($level)
    {
    }

    public function getVerbosity(): int
    {
        return 1;
    }

    public function isQuiet(): bool
    {
        return false;
    }

    public function isVerbose(): bool
    {
        return false;
    }

    public function isVeryVerbose(): bool
    {
        return false;
    }

    public function isDebug(): bool
    {
        return false;
    }

    public function setDecorated($decorated)
    {
    }

    public function isDecorated(): bool
    {
        return false;
    }

    public function setFormatter(OutputFormatterInterface $formatter)
    {
    }

    public function getFormatter(): OutputFormatterInterface
    {
    }
}

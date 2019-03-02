<?php

namespace Laravel\BrowserKitTesting\Tests\Stubs;

use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Formatter\OutputFormatterInterface;

class OutputStub implements OutputInterface
{
    public function write($messages, $newline = false, $options = 0) {}
    public function writeln($messages, $options = 0) {}
    public function setVerbosity($level) {}
    public function getVerbosity() {}
    public function isQuiet() {}
    public function isVerbose() {}
    public function isVeryVerbose() {}
    public function isDebug() {}
    public function setDecorated($decorated) {}
    public function isDecorated() {}
    public function setFormatter(OutputFormatterInterface $formatter) {}
    public function getFormatter() {}
}

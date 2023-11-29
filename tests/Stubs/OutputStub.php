<?php

namespace Laravel\BrowserKitTesting\Tests\Stubs;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Formatter\OutputFormatterInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Traversable;

if (property_exists(Command::class, 'defaultName')) {
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
} else {
    class OutputStub implements OutputInterface
    {
        public function write(Traversable|array|string $messages, bool $newline = false, int $options = 0): void
        {
        }

        public function writeln(Traversable|array|string $messages, int $options = 0): void
        {
        }

        public function setVerbosity(int $level): void
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

        public function setDecorated(bool $decorated): void
        {
        }

        public function isDecorated(): bool
        {
            return false;
        }

        public function setFormatter(OutputFormatterInterface $formatter): void
        {
        }

        public function getFormatter(): OutputFormatterInterface
        {
        }
    }
}

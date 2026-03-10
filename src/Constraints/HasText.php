<?php

namespace Laravel\BrowserKitTesting\Constraints;

use Symfony\Component\DomCrawler\Crawler;

class HasText extends PageConstraint
{
    /**
     * The expected text.
     */
    protected readonly string $text;

    /**
     * Create a new constraint instance.
     *
     * @param  string  $text
     * @return void
     */
    public function __construct($text)
    {
        $this->text = $text;
    }

    /**
     * Check if the plain text is found in the given crawler.
     *
     * @param  Crawler|string  $crawler
     */
    protected function matches($crawler): bool
    {
        $pattern = $this->getEscapedPattern($this->text);

        return preg_match("/{$pattern}/i", $this->text($crawler));
    }

    /**
     * Returns a string representation of the object.
     */
    public function toString(): string
    {
        return "the text [{$this->text}]";
    }
}

<?php

namespace Laravel\BrowserKitTesting\Constraints;

readonly class HasText extends PageConstraint
{
    /**
     * Create a new constraint instance.
     *
     * @param  string  $text
     * @return void
     */
    public function __construct(
        protected string $text
    ) {
        //
    }

    /**
     * Check if the plain text is found in the given crawler.
     *
     * @param  \Symfony\Component\DomCrawler\Crawler|string  $crawler
     * @return bool
     */
    protected function matches($crawler): bool
    {
        $pattern = $this->getEscapedPattern($this->text);

        return preg_match("/{$pattern}/i", $this->text($crawler));
    }

    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function toString(): string
    {
        return "the text [{$this->text}]";
    }
}

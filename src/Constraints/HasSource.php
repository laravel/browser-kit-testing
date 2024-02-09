<?php

namespace Laravel\BrowserKitTesting\Constraints;

class HasSource extends PageConstraint
{
    /**
     * The expected HTML source.
     *
     * @var string
     */
    protected readonly string $source;

    /**
     * Create a new constraint instance.
     *
     * @param  string  $source
     * @return void
     */
    public function __construct($source)
    {
        $this->source = $source;
    }

    /**
     * Check if the source is found in the given crawler.
     *
     * @param  \Symfony\Component\DomCrawler\Crawler|string  $crawler
     * @return bool
     */
    protected function matches($crawler): bool
    {
        $pattern = $this->getEscapedPattern($this->source);

        return preg_match("/{$pattern}/i", $this->html($crawler));
    }

    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function toString(): string
    {
        return "the HTML [{$this->source}]";
    }
}

<?php

namespace Laravel\BrowserKitTesting\Constraints;

class HasTextInOrder extends PageConstraint {
    /**
     * The expected sequance.
     *
     * @var array
     */
    protected $expected;

    /**
     * Create a new constraint instance.
     *
     * @param  array  $expected
     * @return void
     */
    public function __construct($expected)
    {
        $this->expected = $expected;
    }

    /**
     * Check if the plain text sequance is found in the given crawler.
     *
     * @param  \Symfony\Component\DomCrawler\Crawler|string  $crawler
     * @return bool
     */
    protected function matches($crawler)
    {
        $escapedSequance = array_map(function($text) {
            return $this->getEscapedPattern($text);
        }, $this->expected);

        $pattern = join('(?:(.|\n|\r|\s)+?)', $escapedSequance);

        return preg_match("/{$pattern}/i", $this->text($crawler));
    }

    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function toString()
    {
        $text = join(', ', $this->expected);

        return "the sequance [{$text}]";
    }
}

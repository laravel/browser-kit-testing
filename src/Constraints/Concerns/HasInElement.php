<?php

namespace Laravel\BrowserKitTesting\Constraints\Concerns;

use Symfony\Component\DomCrawler\Crawler;

trait HasInElement
{
    /**
     * The name or ID of the element.
     *
     * @var string
     */
    protected readonly string $element;

    /**
     * The text expected to be found.
     *
     * @var string
     */
    protected readonly string $text;

    /**
     * Create a new constraint instance.
     *
     * @param  string  $element
     * @param  string  $text
     * @return void
     */
    public function __construct($element, $text)
    {
        $this->text = $text;
        $this->element = $element;
    }

    /**
     * Check if the source or text is found within the element in the given crawler.
     *
     * @param  \Symfony\Component\DomCrawler\Crawler|string  $crawler
     * @return bool
     */
    public function matches($crawler): bool
    {
        $elements = $this->crawler($crawler)->filter($this->element);

        $pattern = $this->getEscapedPattern($this->text);

        foreach ($elements as $element) {
            $element = new Crawler($element);

            if (preg_match("/$pattern/i", $element->html())) {
                return true;
            }
        }

        return false;
    }

    /**
     * Returns the description of the failure.
     *
     * @return string
     */
    protected function getFailureDescription()
    {
        return sprintf('[%s] contains %s', $this->element, $this->text);
    }

    /**
     * Returns the reversed description of the failure.
     *
     * @return string
     */
    protected function getReverseFailureDescription()
    {
        return sprintf('[%s] does not contain %s', $this->element, $this->text);
    }
}

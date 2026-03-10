<?php

namespace Laravel\BrowserKitTesting\Constraints;

use Symfony\Component\DomCrawler\Crawler;

class IsChecked extends FormFieldConstraint
{
    /**
     * Create a new constraint instance.
     *
     * @param  string  $selector
     * @return void
     */
    public function __construct($selector)
    {
        parent::__construct($selector, null);
    }

    /**
     * Get the valid elements.
     *
     * @return string
     */
    protected function validElements()
    {
        return "input[type='checkbox']";
    }

    /**
     * Determine if the checkbox is checked.
     *
     * @param  Crawler|string  $crawler
     */
    public function matches($crawler): bool
    {
        $crawler = $this->crawler($crawler);

        return ! is_null($this->field($crawler)->attr('checked'));
    }

    /**
     * Return the description of the failure.
     *
     * @return string
     */
    protected function getFailureDescription()
    {
        return "the checkbox [{$this->selector}] is checked";
    }

    /**
     * Returns the reversed description of the failure.
     *
     * @return string
     */
    protected function getReverseFailureDescription()
    {
        return "the checkbox [{$this->selector}] is not checked";
    }
}

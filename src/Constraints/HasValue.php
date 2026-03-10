<?php

namespace Laravel\BrowserKitTesting\Constraints;

use PHPUnit\Framework\ExpectationFailedException;
use Symfony\Component\DomCrawler\Crawler;

class HasValue extends FormFieldConstraint
{
    /**
     * Get the valid elements.
     *
     * @return string
     */
    protected function validElements()
    {
        return 'input,textarea';
    }

    /**
     * Check if the input contains the expected value.
     *
     * @param  Crawler|string  $crawler
     */
    public function matches($crawler): bool
    {
        $crawler = $this->crawler($crawler);

        return $this->getInputOrTextAreaValue($crawler) == $this->value;
    }

    /**
     * Get the value of an input or textarea.
     *
     * @return string
     *
     * @throws ExpectationFailedException
     */
    public function getInputOrTextAreaValue(Crawler $crawler)
    {
        $field = $this->field($crawler);

        return $field->nodeName() == 'input'
            ? $field->attr('value')
            : $field->text();
    }

    /**
     * Return the description of the failure.
     *
     * @return string
     */
    protected function getFailureDescription()
    {
        return sprintf(
            'the field [%s] contains the expected value [%s]',
            $this->selector, $this->value
        );
    }

    /**
     * Returns the reversed description of the failure.
     *
     * @return string
     */
    protected function getReverseFailureDescription()
    {
        return sprintf(
            'the field [%s] does not contain the expected value [%s]',
            $this->selector, $this->value
        );
    }
}

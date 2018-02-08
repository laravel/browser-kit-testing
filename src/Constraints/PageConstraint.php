<?php

namespace Laravel\BrowserKitTesting\Constraints;

use Symfony\Component\DomCrawler\Crawler;
use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Framework\ExpectationFailedException;
use SebastianBergmann\Comparator\ComparisonFailure;

abstract class PageConstraint extends Constraint
{
    /**
     * Make sure we obtain the HTML from the crawler or the response.
     *
     * @param  \Symfony\Component\DomCrawler\Crawler|string  $crawler
     * @return string
     */
    protected function html($crawler)
    {
        return is_object($crawler) ? $crawler->html() : $crawler;
    }

    /**
     * Make sure we obtain the HTML from the crawler or the response.
     *
     * @param  \Symfony\Component\DomCrawler\Crawler|string  $crawler
     * @return string
     */
    protected function text($crawler)
    {
        return is_object($crawler) ? $crawler->text() : strip_tags($crawler);
    }

    /**
     * Create a crawler instance if the given value is not already a Crawler.
     *
     * @param  \Symfony\Component\DomCrawler\Crawler|string  $crawler
     * @return \Symfony\Component\DomCrawler\Crawler
     */
    protected function crawler($crawler)
    {
        return is_object($crawler) ? $crawler : new Crawler($crawler);
    }

    /**
     * Get the escaped text pattern for the constraint.
     *
     * @param  string  $text
     * @return string
     */
    protected function getEscapedPattern($text)
    {
        $rawPattern = preg_quote($text, '/');

        $escapedPattern = preg_quote(e($text), '/');

        return $rawPattern == $escapedPattern
            ? $rawPattern : "({$rawPattern}|{$escapedPattern})";
    }

    /**
     * Throw an exception for the given comparison and test description.
     *
     * @param  \Symfony\Component\DomCrawler\Crawler|string  $crawler
     * @param  string  $description
     * @param  \SebastianBergmann\Comparator\ComparisonFailure|null  $comparisonFailure
     * @return void
     *
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    protected function fail($crawler, $description, ComparisonFailure $comparisonFailure = null): void
    {
        $html = $this->html($crawler);

        $failureDescription = sprintf(
            "%s\n\n\nFailed asserting that %s",
            $html, $this->getFailureDescription()
        );

        if (! empty($description)) {
            $failureDescription .= ": {$description}";
        }

        if (trim($html) != '') {
            $failureDescription .= '. Please check the content above.';
        } else {
            $failureDescription .= '. The response is empty.';
        }

        throw new ExpectationFailedException($failureDescription, $comparisonFailure);
    }

    /**
     * Get the description of the failure.
     *
     * @return string
     */
    protected function getFailureDescription()
    {
        return 'the page contains '.$this->toString();
    }

    /**
     * Returns the reversed description of the failure.
     *
     * @return string
     */
    protected function getReverseFailureDescription()
    {
        return 'the page does not contain '.$this->toString();
    }

    /**
     * Get a string representation of the object.
     *
     * Placeholder method to avoid forcing definition of this method.
     *
     * @return string
     */
    public function toString(): string
    {
        return '';
    }
}

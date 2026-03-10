<?php

namespace Laravel\BrowserKitTesting\Constraints;

use Symfony\Component\DomCrawler\Crawler;

class ReversePageConstraint extends PageConstraint
{
    /**
     * The page constraint instance.
     */
    protected readonly PageConstraint $pageConstraint;

    /**
     * Create a new reverse page constraint instance.
     *
     * @return void
     */
    public function __construct(PageConstraint $pageConstraint)
    {
        $this->pageConstraint = $pageConstraint;
    }

    /**
     * Reverse the original page constraint result.
     *
     * @param  Crawler  $crawler
     */
    public function matches($crawler): bool
    {
        return ! (fn () => $this->matches($crawler))->call($this->pageConstraint);
    }

    /**
     * Get the description of the failure.
     *
     * This method will attempt to negate the original description.
     *
     * @return string
     */
    protected function getFailureDescription()
    {
        return (fn () => $this->getReverseFailureDescription())->call($this->pageConstraint);
    }

    /**
     * Get a string representation of the object.
     */
    public function toString(): string
    {
        return $this->pageConstraint->toString();
    }
}

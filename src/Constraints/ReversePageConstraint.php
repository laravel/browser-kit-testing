<?php

namespace Laravel\BrowserKitTesting\Constraints;

readonly class ReversePageConstraint extends PageConstraint
{
    /**
     * Create a new reverse page constraint instance.
     *
     * @param  \Laravel\BrowserKitTesting\Constraints\PageConstraint  $pageConstraint
     * @return void
     */
    public function __construct(
        protected PageConstraint $pageConstraint
    ) {
        //
    }

    /**
     * Reverse the original page constraint result.
     *
     * @param  \Symfony\Component\DomCrawler\Crawler  $crawler
     * @return bool
     */
    public function matches($crawler): bool
    {
        return ! $this->pageConstraint->matches($crawler);
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
        return $this->pageConstraint->getReverseFailureDescription();
    }

    /**
     * Get a string representation of the object.
     *
     * @return string
     */
    public function toString(): string
    {
        return $this->pageConstraint->toString();
    }
}

<?php

namespace Laravel\BrowserKitTesting\Constraints;

class ReversePageConstraint extends PageConstraint
{
    /**
     * The page constraint instance.
     *
     * @var \Laravel\BrowserKitTesting\Constraints\PageConstraint
     */
    protected readonly PageConstraint $pageConstraint;

    /**
     * Create a new reverse page constraint instance.
     *
     * @param  \Laravel\BrowserKitTesting\Constraints\PageConstraint  $pageConstraint
     * @return void
     */
    public function __construct(PageConstraint $pageConstraint)
    {
        $this->pageConstraint = $pageConstraint;
    }

    /**
     * Reverse the original page constraint result.
     *
     * @param  \Symfony\Component\DomCrawler\Crawler  $crawler
     * @return bool
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
     *
     * @return string
     */
    public function toString(): string
    {
        return $this->pageConstraint->toString();
    }
}

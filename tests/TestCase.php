<?php

namespace Laravel\BrowserKitTesting\Tests;

use DOMDocument;
use Symfony\Component\DomCrawler\Crawler;
use PHPUnit\Framework\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    /**
     * @var \DOMDocument
     */
    protected $dom;

    /**
     * @var \Symfony\Component\DomCrawler\Crawler
     */
    protected $crawler;

    /**
     * @var string
     */
    public $baseUrl = 'https://localhost';

    /**
     * Create a new Page and assign to crawler attribute
     *
     * @return \Symfony\Component\DomCrawler\Crawler
     */
    protected function createPage($html, $uri = 'https://localhost')
    {
        if (empty($this->crawler)) {
            $this->dom = new DOMDocument;
            $this->dom->loadHtml($html);

            $this->crawler = new Crawler($this->dom, $uri);
        }

        return $this->crawler;
    }
}

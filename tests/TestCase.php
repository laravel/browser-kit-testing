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

    public $baseUrl = 'https://localhost';

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

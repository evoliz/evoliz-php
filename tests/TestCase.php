<?php

namespace Tests;

use Evoliz\Client\HttpClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use PHPUnit\Framework\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    protected function mockGuzzle(array $mocks): MockHandler
    {
        $guzzleMock = new MockHandler($mocks);

        $handlerStack = HandlerStack::create($guzzleMock);

        HttpClient::setInstance(['verify' => false, 'handler' => $handlerStack]);

        return $guzzleMock;
    }
}

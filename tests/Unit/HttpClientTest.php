<?php

namespace Tests\Unit;

use Evoliz\Client\HttpClient;
use Tests\TestCase;

class HttpClientTest extends TestCase
{
    public function testGetInstanceReturnNewInstance()
    {
        $httpClient = HttpClient::getInstance();

        $this->assertInstanceOf(HttpClient::class, $httpClient);
    }

    public function testSetInstanceOverrideDefaultConfig()
    {
        $newConfig = [
            'verify' => false,
        ];

        $newHeader = [
            'content-type' => 'application/xml',
        ];

        HttpClient::setInstance($newConfig, $newHeader);

        $this->assertEquals(false, HttpClient::getInstance()->getConfig('verify'));
        $this->assertEquals('application/xml', HttpClient::getInstance()->getConfig()['headers']['content-type']);
    }
}

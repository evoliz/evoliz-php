<?php

namespace Tests\Feature;

use DateTime;
use DateTimeZone;
use Evoliz\Client\Config;
use Evoliz\Client\Exception\ConfigException;
use Faker\Factory;
use Faker\Generator;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

class ConfigTest extends TestCase
{
    /**
     * @var Generator
     */
    private $faker;

    /**
     * @var integer
     */
    private $companyId;

    /**
     * @var string
     */
    private $accessToken;

    /**
     * @var string
     */
    private $expirationDate;

    /**
     * @throws Exception
     */
    public function setUp()
    {
        parent::setUp();

        $this->faker = Factory::create();
        $this->companyId = $this->faker->randomNumber(5);
        $this->accessToken = $this->faker->uuid;

        $tomorrow = new DateTime('tomorrow', new DateTimeZone('Europe/Paris'));
        $this->expirationDate = str_replace('+01:00', '.000000Z', $tomorrow->format(DateTime::ATOM));
    }

    /**
     * @runInSeparateProcess
     * @throws ConfigException
     * @throws Exception
     */
    public function testAuthenticateWithoutValidToken()
    {
        // Setup expired Token to test authenticate method without valid token
        $yesterday = new DateTime('yesterday', new DateTimeZone('Europe/Paris'));
        $yesterday = str_replace('+01:00', '.000000Z', $yesterday->format(DateTime::ATOM));

        $expiredToken = json_encode([
            'access_token' => $this->faker->uuid,
            'expires_at' => $yesterday
        ]);

        $validToken = json_encode([
            'access_token' => $this->accessToken,
            'expires_at' => $this->expirationDate
        ]);

        $guzzleMock = new MockHandler([
            new Response(200, [], $expiredToken),
            new Response(200, [], $validToken),
        ]);

        $handlerStack = HandlerStack::create($guzzleMock);

        $config = new Config($this->companyId, 'EVOLIZ_PUBLIC_KEY', 'EVOLIZ_SECRET_KEY', false, $handlerStack);
        $config->authenticate();
        $this->assertTrue($config->getAccessToken()->isExpired());

        $config = $config->authenticate();
        $this->assertFalse($config->getAccessToken()->isExpired());

        $this->assertEquals($config->getAccessToken()->getToken(), $this->accessToken);
        $this->assertEquals($config->getAccessToken()->getExpiresAt(), new DateTime($this->expirationDate));
    }

    /**
     * @runInSeparateProcess
     * @throws ConfigException
     * @throws Exception
     */
    public function testAuthenticateWithValidToken()
    {
        $validToken = json_encode([
            'access_token' => $this->accessToken,
            'expires_at' => $this->expirationDate
        ]);

        $guzzleMock = new MockHandler([
            new Response(200, [], $validToken),
        ]);

        $handlerStack = HandlerStack::create($guzzleMock);

        $baseConfig = new Config($this->companyId, 'EVOLIZ_PUBLIC_KEY', 'EVOLIZ_SECRET_KEY', false, $handlerStack);
        $baseConfig->authenticate();
        $this->assertFalse($baseConfig->getAccessToken()->isExpired());

        $config = $baseConfig->authenticate();
        $this->assertEquals($baseConfig->getAccessToken()->getToken(), $config->getAccessToken()->getToken());

        $this->assertEquals($config->getAccessToken()->getToken(), $this->accessToken);
        $this->assertEquals($config->getAccessToken()->getExpiresAt(), new DateTime($this->expirationDate));
    }

    /**
     * @throws ConfigException
     * @throws Exception
     */
    public function testAuthenticateWithValidCookieToken()
    {
        $_COOKIE['evoliz_token_' . $this->companyId] = json_encode([
            'access_token' => $this->accessToken,
            'expires_at' => $this->expirationDate
        ]);

        $config = new Config($this->companyId, 'EVOLIZ_PUBLIC_KEY', 'EVOLIZ_SECRET_KEY', false);
        $config->authenticate();
        $this->assertFalse($config->getAccessToken()->isExpired());

        $config = $config->authenticate();

        $this->assertEquals($config->getAccessToken()->getToken(), $this->accessToken);
        $this->assertEquals($config->getAccessToken()->getExpiresAt(), new DateTime($this->expirationDate));
    }

    /**
     * @runInSeparateProcess
     */
    public function testSuccessfulAuthenticateMustSetCookie()
    {
        $validToken = json_encode([
            'access_token' => $this->accessToken,
            'expires_at' => $this->expirationDate
        ]);

        $guzzleMock = new MockHandler([
            new Response(200, [], $validToken),
        ]);

        $handlerStack = HandlerStack::create($guzzleMock);

        $config = new Config($this->companyId, 'EVOLIZ_PUBLIC_KEY', 'EVOLIZ_SECRET_KEY', false, $handlerStack);

        $config->authenticate();

        $setCookieHeader = urldecode(xdebug_get_headers()[0]);

        $this->assertEquals('Set-Cookie: evoliz_token_' .  $this->companyId . '=' . $validToken, $setCookieHeader);
    }
}

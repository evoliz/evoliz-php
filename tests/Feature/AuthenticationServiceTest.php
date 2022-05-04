<?php

namespace Tests\Feature;

use DateTime;
use DateTimeZone;
use Evoliz\Client\Config;
use Evoliz\Client\Exception\AuthenticationException;
use Evoliz\Client\Services\AuthenticationService;
use Faker\Factory;
use Faker\Generator;
use GuzzleHttp\Psr7\Response;
use Tests\TestCase;

class AuthenticationServiceTest extends TestCase
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
     * @throws AuthenticationException
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

        $this->mockGuzzle([
            new Response(200, [], $expiredToken),
            new Response(200, [], $validToken),
        ]);

        $config = new Config($this->companyId, 'EVOLIZ_PUBLIC_KEY', 'EVOLIZ_SECRET_KEY');
        $authenticationService = new AuthenticationService($config);
        $authenticationService->authenticate();
        $this->assertTrue($config->getAccessToken()->isExpired());

        $authenticationService->authenticate();
        $this->assertFalse($config->getAccessToken()->isExpired());

        $this->assertEquals($config->getAccessToken()->getToken(), $this->accessToken);
        $this->assertEquals($config->getAccessToken()->getExpiresAt(), new DateTime($this->expirationDate));
    }

    /**
     * @runInSeparateProcess
     * @throws AuthenticationException
     * @throws Exception
     */
    public function testAuthenticateWithValidToken()
    {
        $validToken = json_encode([
            'access_token' => $this->accessToken,
            'expires_at' => $this->expirationDate
        ]);

        $this->mockGuzzle([
            new Response(200, [], $validToken),
        ]);

        $baseConfig = new Config($this->companyId, 'EVOLIZ_PUBLIC_KEY', 'EVOLIZ_SECRET_KEY');
        $authenticationService = new AuthenticationService($baseConfig);
        $authenticationService->authenticate();
        $this->assertFalse($baseConfig->getAccessToken()->isExpired());

        $authenticationService->authenticate();
        $this->assertEquals($baseConfig->getAccessToken()->getToken(), $baseConfig->getAccessToken()->getToken());

        $this->assertEquals($baseConfig->getAccessToken()->getToken(), $this->accessToken);
        $this->assertEquals($baseConfig->getAccessToken()->getExpiresAt(), new DateTime($this->expirationDate));
    }

    /**
     * @throws AuthenticationException
     * @throws Exception
     */
    public function testAuthenticateWithValidCookieToken()
    {
        $_COOKIE['evoliz_token_' . $this->companyId] = json_encode([
            'access_token' => $this->accessToken,
            'expires_at' => $this->expirationDate
        ]);

        $config = new Config($this->companyId, 'EVOLIZ_PUBLIC_KEY', 'EVOLIZ_SECRET_KEY', false);
        $authenticationService = new AuthenticationService($config);
        $authenticationService->authenticate();
        $this->assertFalse($config->getAccessToken()->isExpired());

        $authenticationService->authenticate();

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

        $this->mockGuzzle([
            new Response(200, [], $validToken),
        ]);

        $config = new Config($this->companyId, 'EVOLIZ_PUBLIC_KEY', 'EVOLIZ_SECRET_KEY');

        (new AuthenticationService($config))->authenticate();

        $setCookieHeader = urldecode(xdebug_get_headers()[0]);

        $this->assertEquals('Set-Cookie: evoliz_token_' .  $this->companyId . '=' . $validToken, $setCookieHeader);
    }

    /**
     * @throws AuthenticationException|Exception
     */
    public function testLoginWithInvalidCredentials()
    {
        $this->mockGuzzle([
            new Response(401, [], 'Unauthenticated'),
        ]);

        $this->expectException(AuthenticationException::class);
        $config = new Config($this->companyId, 'EVOLIZ_PUBLIC_KEY', 'EVOLIZ_SECRET_KEY', false);
        (new AuthenticationService($config))->authenticate();
    }
}

<?php

use Evoliz\Client\AccessToken;
use Evoliz\Client\Config;
use Evoliz\Client\Exception\ConfigException;
use PHPUnit\Framework\TestCase;

class ConfigTest extends TestCase
{
    /**
     * @var \Faker\Generator
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

    public function setUp()
    {
        parent::setUp();

        $this->faker = Faker\Factory::create();
        $this->companyId = $this->faker->randomNumber(5);
        $this->accessToken = $this->faker->uuid;

        $tomorrow = new DateTime('tomorrow', new DateTimeZone('Europe/Paris'));
        $this->expirationDate = str_replace('+01:00', '.000000Z', $tomorrow->format(DateTime::ATOM));
    }

    /**
     * @throws ConfigException|Exception
     */
    public function testHasValidCookieToken()
    {
        $_COOKIE['evoliz_token_' . $this->companyId] = json_encode([
            'access_token' => $this->accessToken,
            'expires_at' => $this->expirationDate
        ]);

        $config = new Config($this->companyId, 'EVOLIZ_PUBLIC_KEY', 'EVOLIZ_SECRET_KEY', false);

        $guzzleClientToken = explode(' ', $config->getClient()->getConfig('headers')['Authorization'])[1];

        $this->assertEquals($config->getAccessToken()->getToken(), $this->accessToken);
        $this->assertEquals($config->getAccessToken()->getExpiresAt(), new DateTime($this->expirationDate));

        $this->assertEquals($guzzleClientToken, $this->accessToken);
    }

    /**
     * @throws ConfigException|Exception
     */
    public function testLoginWithInvalidCredentials()
    {
        $this->expectException(ConfigException::class);
        new Config($this->companyId, 'EVOLIZ_PUBLIC_KEY', 'EVOLIZ_SECRET_KEY', false);
    }

    /**
     * @throws ConfigException
     */
    public function testLoginWithValidCredentials()
    {
        $mock = Mockery::mock('Evoliz\Client\Config')->shouldAllowMockingProtectedMethods();

        $token = new AccessToken($this->accessToken, $this->expirationDate);

        $mock->shouldReceive('login')
            ->once()->andReturn($token);

        $mock->shouldReceive('setcookie')
            ->once()->andReturn($_COOKIE['evoliz_token_' . $this->companyId] = json_encode([
                'access_token' => $this->accessToken,
                'expires_at' => $this->expirationDate
            ]));

        $config = new Config($this->companyId, 'EVOLIZ_PUBLIC_KEY', 'EVOLIZ_SECRET_KEY', false);

        $this->assertEquals($config->getAccessToken()->getToken(), $this->accessToken);
        $this->assertEquals($config->getAccessToken()->getExpiresAt(), new DateTime($this->expirationDate));

        $guzzleClientToken = explode(' ', $config->getClient()->getConfig('headers')['Authorization'])[1];
        $this->assertEquals($config->getAccessToken()->getToken(), $guzzleClientToken);
    }

    /**
     * @return void
     * @throws ConfigException
     */
    public function testSetDefaultReturnType()
    {
        $_COOKIE['evoliz_token_' . $this->companyId] = json_encode([
            'access_token' => $this->accessToken,
            'expires_at' => $this->expirationDate
        ]);

        $config = new Config($this->companyId, 'EVOLIZ_PUBLIC_KEY', 'EVOLIZ_SECRET_KEY', false);

        $config->setDefaultReturnType($config::OBJECT_RETURN_TYPE);
        $this->assertEquals($config::OBJECT_RETURN_TYPE, $config->getDefaultReturnType());
    }

    /**
     * @throws ConfigException
     */
    public function testSetDefaultReturnTypeWithUnallowedReturnType()
    {
        $_COOKIE['evoliz_token_' . $this->companyId] = json_encode([
            'access_token' => $this->accessToken,
            'expires_at' => $this->expirationDate
        ]);

        $config = new Config($this->companyId, 'EVOLIZ_PUBLIC_KEY', 'EVOLIZ_SECRET_KEY', false);

        $this->expectException(ConfigException::class);
        $config->setDefaultReturnType('JAR');
    }
}

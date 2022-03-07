<?php

use Evoliz\Client\Config;
use Evoliz\Client\Exception\LoginException;
use PHPUnit\Framework\TestCase;

class ConfigTest extends TestCase
{
    // @Todo : Fichier de conf
    const EVOLIZ_PUBLIC_KEY = '620bad8b54a5f776255068e9YjGLZB3s';
    const EVOLIZ_SECRET_KEY = 'ab56f9d148303f86805406a77ac620bfYcCQUOSrbzK25pl7L9';

    /**
     * @throws LoginException
     */
    public function testHasValidCookieToken()
    {
        $faker = Faker\Factory::create();

        $companyId = $faker->randomNumber(5);
        $accessToken = $faker->uuid;
        $tomorrow = new DateTime('tomorrow', new DateTimeZone('Europe/Paris'));
        $expirationDate = str_replace('+01:00', '.000000Z', $tomorrow->format(DateTime::ATOM));

        $_COOKIE['evoliz_token_' . $companyId] = json_encode([
            'access_token' => $accessToken,
            'expires_at' => $expirationDate
        ]);

        $config = new Config($companyId, 'test', 'non', false);

        $guzzleClientToken = explode(' ', $config->getClient()->getConfig('headers')['Authorization'])[1];

        $this->assertEquals($config->getAccessToken()->getToken(), $accessToken);
        $this->assertEquals($config->getAccessToken()->getExpiresAt(), new DateTime($expirationDate));

        $this->assertEquals($guzzleClientToken, $accessToken);
    }

    /**
     * @throws LoginException
     */
    public function testLoginWithInvalidCredentials()
    {
        $faker = Faker\Factory::create();

        $companyId = $faker->randomNumber(5);

        $this->expectException(LoginException::class);
        new Config($companyId, $faker->uuid, $faker->uuid, false);
    }

    /**
     * @throws LoginException
     */
    public function testLoginWithValidCredentials()
    {
        $faker = Faker\Factory::create();
        $companyId = $faker->randomNumber(5);
        $accessToken = $faker->uuid;
        $tomorrow = new DateTime('tomorrow', new DateTimeZone('Europe/Paris'));
        $expirationDate = str_replace('+01:00', '.000000Z', $tomorrow->format(DateTime::ATOM));

        $mock = Mockery::mock('Evoliz\Client\Config');
        $mock->shouldReceive('setcookie')
            ->once()->andReturn($_COOKIE['evoliz_token_' . $companyId] = json_encode([
                'access_token' => $accessToken,
                'expires_at' => $expirationDate
            ]));

        $config = new Config($companyId, self::EVOLIZ_PUBLIC_KEY, self::EVOLIZ_SECRET_KEY, false);

        $this->assertEquals($config->getAccessToken()->getToken(), $accessToken);
        $this->assertEquals($config->getAccessToken()->getExpiresAt(), new DateTime($expirationDate));

        $guzzleClientToken = explode(' ', $config->getClient()->getConfig('headers')['Authorization'])[1];
        $this->assertEquals($config->getAccessToken()->getToken(), $guzzleClientToken);
    }
}

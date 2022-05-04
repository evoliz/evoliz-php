<?php

namespace Tests\Unit;

use Evoliz\Client\Config;
use Evoliz\Client\Exception\ConfigException;
use Faker\Factory;
use Faker\Generator;
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
     * @throws Exception
     */
    public function setUp()
    {
        parent::setUp();
        $this->faker = Factory::create();
        $this->companyId = $this->faker->randomNumber(5);
    }

    /**
     * @throws ConfigException|Exception
     */
    public function testLoginWithInvalidCredentials()
    {
        $this->expectException(ConfigException::class);
        (new Config($this->companyId, 'EVOLIZ_PUBLIC_KEY', 'EVOLIZ_SECRET_KEY', false))
            ->authenticate();
    }

    /**
     * @runInSeparateProcess
     * @return void
     * @throws ConfigException
     */
    public function testSetDefaultReturnType()
    {
        $config = new Config($this->companyId, 'EVOLIZ_PUBLIC_KEY', 'EVOLIZ_SECRET_KEY', false);
        $this->assertEquals($config::OBJECT_RETURN_TYPE, $config->getDefaultReturnType());

        $config->setDefaultReturnType($config::JSON_RETURN_TYPE);
        $this->assertEquals($config::JSON_RETURN_TYPE, $config->getDefaultReturnType());

        $this->expectException(ConfigException::class);
        $config->setDefaultReturnType('EVZ');
    }
}

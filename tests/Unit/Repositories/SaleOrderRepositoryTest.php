<?php

namespace Tests\Unit\Repositories;

use DateTime;
use DateTimeZone;
use Evoliz\Client\Config;
use Evoliz\Client\Exception\ConfigException;
use Evoliz\Client\Exception\ResourceException;
use Evoliz\Client\Repository\Sales\SaleOrderRepository;
use Evoliz\Client\Response\Sales\InvoiceResponse;
use Faker\Factory;
use GuzzleHttp\Psr7\Response;
use Tests\TestCase;

class SaleOrderRepositoryTest extends TestCase
{
    /**
     * @var integer
     */
    private $companyId;

    /**
     * @var Factory
     */
    private $faker;

    /**
     * @throws \Exception
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->faker = Factory::create();
        $this->companyId = $this->faker->randomNumber(5);
        $accessToken = $this->faker->uuid;

        $tomorrow = new DateTime('tomorrow', new DateTimeZone('Europe/Paris'));
        $expirationDate = str_replace('+01:00', '.000000Z', $tomorrow->format(DateTime::ATOM));

        $_COOKIE['evoliz_token_' . $this->companyId] = json_encode([
            'access_token' => $accessToken,
            'expires_at' => $expirationDate
        ]);
    }

    /**
     * @throws ConfigException|ResourceException|\Exception
     */
    public function testSuccessfulInvoicingMustReturnInvoice()
    {
        $response = json_encode([
            'invoiceid' => $this->faker->randomNumber(5),
        ]);

        $saleOrderId = $this->faker->randomNumber(5);

        $this->mockGuzzle([
            new Response(201, [], $response),
            new Response(201, [], $response),
            new Response(201, [], $response),
        ]);

        $config = new Config($this->companyId, 'EVOLIZ_PUBLIC_KEY', 'EVOLIZ_SECRET_KEY');

        $saleOrderRepository = new SaleOrderRepository($config);

        $invoice = $saleOrderRepository->invoice($saleOrderId);

        $this->assertInstanceOf(InvoiceResponse::class, $invoice);

        $invoice = $saleOrderRepository->invoice($saleOrderId, true);

        $this->assertInstanceOf(InvoiceResponse::class, $invoice);
    }
}

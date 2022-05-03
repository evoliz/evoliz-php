<?php

namespace Tests\Unit\Repositories;

use DateTime;
use DateTimeZone;
use Evoliz\Client\Config;
use Evoliz\Client\Repository\Sales\SaleOrderRepository;
use Evoliz\Client\Response\Sales\InvoiceResponse;
use Faker\Factory;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

class SaleOrderRepositoryTest extends TestCase
{
    /**
     * @var integer
     */
    private $companyId;

    private $faker;

    /**
     * @throws \Exception
     */
    public function setUp()
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

    public function testSuccessfulInvoicingMustReturnInvoice()
    {
        $response = json_encode([
            'data' => [
                0 => [
                    'invoiceid' => $this->faker->randomNumber(5),
                ],
            ],
            'links' => [],
            'meta' => [],
        ]);

        $saleOrderId = $this->faker->randomNumber(5);

        $guzzleMock = new MockHandler([
            new Response(201, [], $response),
        ]);

        $handlerStack = HandlerStack::create($guzzleMock);

        $config = new Config($this->companyId, 'EVOLIZ_PUBLIC_KEY', 'EVOLIZ_SECRET_KEY', false, $handlerStack);

        $saleOrderRepository = new SaleOrderRepository($config);

        $invoice = $saleOrderRepository->invoice($saleOrderId);

        $this->assertInstanceOf(InvoiceResponse::class, $invoice);
    }
}

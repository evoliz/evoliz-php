<?php

namespace Tests\Unit\Repositories;

use DateTime;
use DateTimeZone;
use Evoliz\Client\Config;
use Evoliz\Client\Exception\ConfigException;
use Evoliz\Client\Exception\ResourceException;
use Evoliz\Client\Repository\Sales\InvoiceRepository;
use Evoliz\Client\Response\Sales\InvoiceResponse;
use Evoliz\Client\Response\Sales\PaymentResponse;
use Faker\Factory;
use GuzzleHttp\Psr7\Response;
use Tests\TestCase;

class InvoiceRepositoryTest extends TestCase
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
     * @throws ResourceException|ConfigException|\Exception
     */
    public function testSuccessfulSavingMustReturnInvoice()
    {
        $response = json_encode([
            'invoiceid' => $this->faker->randomNumber(5),
        ]);

        $invoiceId = $this->faker->randomNumber(5);

        $this->mockGuzzle([new Response(201, [], $response)]);

        $config = new Config($this->companyId, 'EVOLIZ_PUBLIC_KEY', 'EVOLIZ_SECRET_KEY');

        $invoiceRepository = new InvoiceRepository($config);

        $invoice = $invoiceRepository->save($invoiceId);

        $this->assertInstanceOf(InvoiceResponse::class, $invoice);
    }

    /**
     * @throws ResourceException|ConfigException|\Exception
     */
    public function testSuccessfulPayMustReturnPayment()
    {
        $response = json_encode([
            'paymentid' => $this->faker->randomNumber(5),
        ]);

        $invoiceId = $this->faker->randomNumber(5);

        $this->mockGuzzle([new Response(201, [], $response)]);

        $config = new Config($this->companyId, 'EVOLIZ_PUBLIC_KEY', 'EVOLIZ_SECRET_KEY');

        $invoiceRepository = new InvoiceRepository($config);

        $payment = $invoiceRepository->pay($invoiceId, 'Payment with the SDK', 1, 10);

        $this->assertInstanceOf(PaymentResponse::class, $payment);
    }
}

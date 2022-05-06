<?php

namespace Tests\Feature;

use DateTime;
use DateTimeZone;
use Evoliz\Client\Config;
use Evoliz\Client\Exception\ConfigException;
use Evoliz\Client\Exception\ResourceException;
use Evoliz\Client\Model\Clients\ContactClient;
use Evoliz\Client\Repository\Clients\ContactClientRepository;
use Evoliz\Client\Response\APIResponse;
use Evoliz\Client\Response\Clients\ContactClientResponse;
use Faker\Factory;
use GuzzleHttp\Psr7\Response;
use Tests\TestCase;

class ContactClientTest extends TestCase
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
        $this->contactId = $this->faker->randomNumber(5);
        $accessToken = $this->faker->uuid;

        $tomorrow = new DateTime('tomorrow', new DateTimeZone('Europe/Paris'));
        $expirationDate = str_replace('+01:00', '.000000Z', $tomorrow->format(DateTime::ATOM));

        $_COOKIE['evoliz_token_' . $this->companyId] = json_encode([
            'access_token' => $accessToken,
            'expires_at' => $expirationDate
        ]);
    }

    /**
     * @throws ConfigException|\Exception|ResourceException
     */
    public function testContactClientListShouldReturnAPIResponseObject()
    {
        $response = json_encode([
            'data' => [
                0 => [
                    'contactid' => $this->contactId,
                ],
            ],
            'links' => [],
            'meta' => [],
        ]);

        $this->mockGuzzle([
            new Response(200, [], $response),
        ]);

        $config = new Config($this->companyId, 'EVOLIZ_PUBLIC_KEY', 'EVOLIZ_SECRET_KEY');
        $contactClientRepository = new ContactClientRepository($config);

        $contactClients = $contactClientRepository->list();
        $this->assertInstanceOf(APIResponse::class, $contactClients);

        $firstContactClient = $contactClients->data[0];
        $this->assertInstanceOf(ContactClientResponse::class, $firstContactClient);
        $this->assertEquals($this->contactId, $contactClients->data[0]->contactid);
    }

    /**
     * @throws ConfigException|\Exception
     */
    public function testContactClientDetailShouldReturnContactClientResponseObject()
    {
        $response = json_encode([
            'contactid' => $this->contactId,
        ]);

        $this->mockGuzzle([
            new Response(200, [], $response),
        ]);

        $config = new Config($this->companyId, 'EVOLIZ_PUBLIC_KEY', 'EVOLIZ_SECRET_KEY');
        $contactClientRepository = new ContactClientRepository($config);

        $contactClient = $contactClientRepository->detail(1);
        $this->assertInstanceOf(ContactClientResponse::class, $contactClient);
        $this->assertEquals($this->contactId, $contactClient->contactid);
    }

    /**
     * @throws ConfigException|\Exception
     */
    public function testContactClientCreateShouldReturnContactClientResponseObject()
    {
        $response = json_encode([
            'contactid' => $this->contactId,
        ]);

        $this->mockGuzzle([
            new Response(200, [], $response),
        ]);

        $config = new Config($this->companyId, 'EVOLIZ_PUBLIC_KEY', 'EVOLIZ_SECRET_KEY');
        $contactClientRepository = new ContactClientRepository($config);

        $contactClient = $contactClientRepository->create(new ContactClient([]));
        $this->assertInstanceOf(ContactClientResponse::class, $contactClient);
    }

    /**
     * @throws ConfigException|\Exception
     */
    public function testContactClientShouldThrowResourceException()
    {
        $errorCode = $this->faker->randomElement([400, 401, 403, 404, 405, 422, 424, 429, 500]);
        $errorLabel = $this->faker->word();
        $errorMessage = $this->faker->sentence;

        $this->mockGuzzle([
            new Response($errorCode, [], json_encode([
                'error' => $errorLabel,
                'message' => $errorMessage
            ])),
        ]);

        $config = new Config($this->companyId, 'EVOLIZ_PUBLIC_KEY', 'EVOLIZ_SECRET_KEY');
        $contactClientRepository = new ContactClientRepository($config);

        $this->expectException(ResourceException::class);
        $this->expectExceptionMessage($errorLabel . ' : ' . $errorMessage);
        $contactClientRepository->list();

        $this->expectException(ResourceException::class);
        $this->expectExceptionMessage($errorLabel . ' : ' . $errorMessage);
        $contactClientRepository->detail(1);

        $this->expectException(ResourceException::class);
        $this->expectExceptionMessage($errorLabel . ' : ' . $errorMessage);
        $contactClientRepository->create(new ContactClient([]));
    }
}

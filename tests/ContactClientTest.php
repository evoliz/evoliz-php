<?php

use Evoliz\Client\Config;
use Evoliz\Client\Exception\ConfigException;
use Evoliz\Client\Exception\ResourceException;
use Evoliz\Client\Model\Clients\ContactClient;
use Evoliz\Client\Repository\Clients\ContactClientRepository;
use Evoliz\Client\Response\APIResponse;
use Evoliz\Client\Response\ContactClient\ContactClientResponse;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

class ContactClientTest extends TestCase
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

    /**
     * @throws Exception
     */
    public function setUp()
    {
        parent::setUp();

        $this->faker = Faker\Factory::create();
        $this->companyId = $this->faker->randomNumber(5);
        $this->accessToken = $this->faker->uuid;

        $tomorrow = new DateTime('tomorrow', new DateTimeZone('Europe/Paris'));
        $this->expirationDate = str_replace('+01:00', '.000000Z', $tomorrow->format(DateTime::ATOM));

        $_COOKIE['evoliz_token_' . $this->companyId] = json_encode([
            'access_token' => $this->accessToken,
            'expires_at' => $this->expirationDate
        ]);
    }

    /**
     * @throws ConfigException|Exception
     */
    public function testContactClientListShouldReturnAPIResponseObject()
    {
        $response = json_encode([
            'data' => [
                0 => [
                    'contactid' => 8568,
                ],
            ],
            'links' => [],
            'meta' => [],
        ]);

        $guzzleMock = new MockHandler([
            new Response(200, [], $response),
        ]);

        $handlerStack = HandlerStack::create($guzzleMock);

        $config = new Config($this->companyId, 'EVOLIZ_PUBLIC_KEY', 'EVOLIZ_SECRET_KEY', false, $handlerStack);
        $contactClientRepository = new ContactClientRepository($config);

        $contactClients = $contactClientRepository->list();
        $this->assertInstanceOf(APIResponse::class, $contactClients);

        $firstContactClient = $contactClients->data[0];
        $this->assertInstanceOf(ContactClientResponse::class, $firstContactClient);
    }

    /**
     * @throws ConfigException|Exception
     */
    public function testContactClientDetailShouldReturnContactClientResponseObject()
    {
        $response = json_encode([
            'contactid' => 8568,
        ]);

        $guzzleMock = new MockHandler([
            new Response(200, [], $response),
        ]);

        $handlerStack = HandlerStack::create($guzzleMock);

        $config = new Config($this->companyId, 'EVOLIZ_PUBLIC_KEY', 'EVOLIZ_SECRET_KEY', false, $handlerStack);
        $contactClientRepository = new ContactClientRepository($config);

        $contactClient = $contactClientRepository->detail(1);
        $this->assertInstanceOf(ContactClientResponse::class, $contactClient);
    }

    /**
     * @throws ConfigException|Exception
     */
    public function testContactClientCreateShouldReturnContactClientResponseObject()
    {
        $response = json_encode([
            'contactid' => 8568,
        ]);

        $guzzleMock = new MockHandler([
            new Response(200, [], $response),
        ]);

        $handlerStack = HandlerStack::create($guzzleMock);

        $config = new Config($this->companyId, 'EVOLIZ_PUBLIC_KEY', 'EVOLIZ_SECRET_KEY', false, $handlerStack);
        $contactClientRepository = new ContactClientRepository($config);

        $contactClient = $contactClientRepository->create(new ContactClient([]));
        $this->assertInstanceOf(ContactClientResponse::class, $contactClient);
    }

    /**
     * @throws ConfigException|Exception
     */
    public function testContactClientShouldThrowResourceException()
    {
        $errorCode = 400;
        $errorLabel = 'Gandalf';
        $errorMessage = 'You Shall Not Pass!';

        $guzzleMock = new MockHandler([
            new Response($errorCode, [], json_encode([
                'error' => $errorLabel,
                'message' => $errorMessage
            ])),
        ]);

        $handlerStack = HandlerStack::create($guzzleMock);

        $config = new Config($this->companyId, 'EVOLIZ_PUBLIC_KEY', 'EVOLIZ_SECRET_KEY', false, $handlerStack);
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

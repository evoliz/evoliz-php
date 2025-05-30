<?php

namespace Tests\Unit\Repositories;

use DateTime;
use DateTimeZone;
use Evoliz\Client\Config;
use Evoliz\Client\Exception\PaginationException;
use Evoliz\Client\Exception\ResourceException;
use Evoliz\Client\Model\BaseModel;
use Evoliz\Client\Repository\BaseRepository;
use Evoliz\Client\Repository\Catalog\ArticleRepository;
use Evoliz\Client\Repository\Clients\ClientRepository;
use Evoliz\Client\Repository\Clients\ContactClientRepository;
use Evoliz\Client\Repository\Sales\InvoiceRepository;
use Evoliz\Client\Repository\Sales\SaleOrderRepository;
use Evoliz\Client\Response\APIResponse;
use Evoliz\Client\Response\BaseResponse;
use Faker\Factory;
use GuzzleHttp\Psr7\Response;
use Tests\TestCase;

class BaseRepositoryTest extends TestCase
{
    /**
     * @var Factory
     */
    private $faker;

    /**
     * @var Config
     */
    private $config;

    /**
     * @var BaseRepository
     */
    private $anonymousRepository;

    /**
     * @throws \Exception
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->faker = Factory::create();
        $companyId = $this->faker->randomNumber(5);
        $accessToken = $this->faker->uuid;

        $tomorrow = new DateTime('tomorrow', new DateTimeZone('Europe/Paris'));
        $expirationDate = str_replace('+01:00', '.000000Z', $tomorrow->format(DateTime::ATOM));

        $_COOKIE['evoliz_token_' . $companyId] = json_encode([
            'access_token' => $accessToken,
            'expires_at' => $expirationDate
        ]);

        $this->config = new Config($companyId, 'EVOLIZ_PUBLIC_KEY', 'EVOLIZ_SECRET_KEY');
        $anonymousResponse = new class ([]) extends BaseResponse {};
        $responseModel = get_class($anonymousResponse);

        $this->anonymousRepository = new class ($this->config, 'anonymous', $responseModel) extends BaseRepository {};
    }

    /**
     * @throws ResourceException|\Exception
     */
    public function testSuccessfulListingMustReturnAPIResponse()
    {
        $anonymousId = $this->faker->randomNumber();

        $response = json_encode([
            'data' => [
                0 => [
                    'anonymousid' => $anonymousId,
                ],
            ],
            'links' => [],
            'meta' => [],
        ]);

        $this->mockGuzzle([
            new Response(200, [], $response),
        ]);

        $anonymousObjects = $this->anonymousRepository->list();
        $this->assertInstanceOf(APIResponse::class, $anonymousObjects);

        $firstAnonymousObject = $anonymousObjects->data[0];
        $this->assertInstanceOf(BaseResponse::class, $firstAnonymousObject);

        $this->assertEquals($anonymousId, $anonymousObjects->data[0]->anonymousid);
    }

    /**
     * @throws ResourceException
     */
    public function testSuccessfulDetailingMustReturnBaseResponse()
    {
        $anonymousId = $this->faker->randomNumber();

        $response = json_encode([
            'anonymousid' => $anonymousId,
        ]);

        $this->mockGuzzle([
            new Response(200, [], $response),
        ]);

        $anonymousObject = $this->anonymousRepository->detail($anonymousId);
        $this->assertInstanceOf(BaseResponse::class, $anonymousObject);
    }

    /**
     * @throws ResourceException
     */
    public function testSuccessfulCreatingMustReturnBaseResponse()
    {
        $anonymousId = $this->faker->randomNumber();

        $response = json_encode([
            'anonymousid' => $anonymousId,
        ]);

        $this->mockGuzzle([
            new Response(200, [], $response),
        ]);

        $anonymousObject = $this->anonymousRepository->create(new class () extends BaseModel {
        });
        $this->assertInstanceOf(BaseResponse::class, $anonymousObject);
    }

    public function testShouldThrowResourceException()
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

        $this->expectException(ResourceException::class);
        $this->expectExceptionMessage($errorLabel . ' : ' . $errorMessage);
        $this->anonymousRepository->list();

        $this->expectException(ResourceException::class);
        $this->expectExceptionMessage($errorLabel . ' : ' . $errorMessage);
        $this->anonymousRepository->detail($this->faker->randomNumber());

        $this->expectException(ResourceException::class);
        $this->expectExceptionMessage($errorLabel . ' : ' . $errorMessage);
        $this->anonymousRepository->create(new class () extends BaseModel {
        });
    }

    /**
     * @dataProvider provideChildRepository
     * @throws \Exception
     */
    public function testBaseRepositoryInheritance($repositoryClass)
    {
        $repository = new $repositoryClass($this->config);
        $parentClass = get_parent_class($repository);

        $this->assertEquals(BaseRepository::class, $parentClass);
    }

    /**
     * @throws ResourceException
     */
    public function testGetNumberOfPages()
    {
        $lastPage = $this->faker->randomNumber();

        $response = json_encode([
            'data' => [],
            'links' => [],
            'meta' => [
                'last_page' => $lastPage
            ],
        ]);

        $mockHandler = $this->mockGuzzle([
            new Response(200, [], $response),
        ]);

        $numberOfPages = $this->anonymousRepository->getNumberOfPages();
        $this->assertEquals($numberOfPages, $lastPage);

        // Asserting that guzzle is called 1 time, so there is no item left in the queue
        $this->assertEquals(0, $mockHandler->count());
    }

    /**
     * @throws PaginationException|ResourceException
     */
    public function testPaginateShouldReturnNullOnNonExistingLastResponse()
    {
        $this->assertNull($this->anonymousRepository->nextPage());
        $this->assertNull($this->anonymousRepository->previousPage());
    }

    /**
     * @throws PaginationException|ResourceException
     */
    public function testPaginateShouldReturnNullOnRequestedPageNullLink()
    {
        $response = json_encode([
            'data' => [
                0 => [
                    'anonymousid' => $this->faker->randomNumber(),
                ],
            ],
            'links' => [
                'first' => null,
                'last' => null,
                'next' => null,
                'prev' => null,
            ],
            'meta' => [],
        ]);

        $mockHandler = $this->mockGuzzle([
            new Response(200, [], $response),
        ]);

        $this->anonymousRepository->list();

        $this->assertNull($this->anonymousRepository->firstPage());
        $this->assertNull($this->anonymousRepository->lastPage());
        $this->assertNull($this->anonymousRepository->nextPage());
        $this->assertNull($this->anonymousRepository->previousPage());

        // Asserting that guzzle is called 1 time, so there is no item left in the queue
        $this->assertEquals(0, $mockHandler->count());
    }

    /**
     * @throws ResourceException
     */
    public function testPaginationKeepQueryParameters()
    {
        $uriWithQueryParams = 'https://www.evoliz.io/api/v1/anonymous?period=currentmonth&per_page=10';

        $response = json_encode([
            'data' => [
                0 => [
                    'anonymousid' => $this->faker->randomNumber(),
                ],
            ],
            'links' => [
                'first' => $uriWithQueryParams . '&page=1',
            ],
            'meta' => [],
        ]);

        $mockHandler = $this->mockGuzzle([
            new Response(200, [], $response),
            new Response(200, [], $response),
        ]);

        $requestedPage = $this->faker->randomNumber();

        $this->anonymousRepository->page($requestedPage);

        $mockHandlerUri = $mockHandler->getLastRequest()->getUri();
        $requestedUri = $mockHandlerUri->getScheme() . '://' . $mockHandlerUri->getHost()
            . $mockHandler->getLastRequest()->getRequestTarget();

        $this->assertEquals($requestedUri, $uriWithQueryParams . '&page=' . $requestedPage);

        // Asserting that guzzle is called 2 times, so there is no item left in the queue
        $this->assertEquals(0, $mockHandler->count());
    }

    public function provideChildRepository(): array
    {
        return [
            'ArticleRepository' => [
                ArticleRepository::class,
            ],
            'ClientRepository' => [
                ClientRepository::class,
            ],
            'ContactClientRepository' => [
                ContactClientRepository::class,
            ],
            'InvoiceRepository' => [
                InvoiceRepository::class,
            ],
            'SaleOrderRepository' => [
                SaleOrderRepository::class,
            ],
        ];
    }
}

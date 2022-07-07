<?php

namespace Tests\Unit\Repositories;

use DateTime;
use DateTimeZone;
use Evoliz\Client\Config;
use Evoliz\Client\Exception\PaginationException;
use Evoliz\Client\Exception\ResourceException;
use Evoliz\Client\Model\BaseModel;
use Evoliz\Client\Repository\BaseRepository;
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
     * @throws \Exception
     */
    public function setUp()
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

        $config = new Config($companyId, 'EVOLIZ_PUBLIC_KEY', 'EVOLIZ_SECRET_KEY');
        $this->anonymousRepository = new class($config, 'anonymous', get_class(new class([]) extends BaseResponse {})) extends BaseRepository {};
    }

    /**
     * @throws ResourceException|\Exception
     */
    public function testSuccessfulListingMustReturnAPIResponse()
    {
        $response = json_encode([
            'data' => [
                0 => [
                    'anonymousid' => $this->faker->randomNumber(),
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

        $anonymousObject = $this->anonymousRepository->create(new class() extends BaseModel {});
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
        $this->anonymousRepository->create(new class() extends BaseModel {});
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

        $this->mockGuzzle([
            new Response(200, [], $response),
        ]);

        $numberOfPages = $this->anonymousRepository->getNumberOfPages();
        $this->assertEquals($numberOfPages, $lastPage);
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

        $this->mockGuzzle([
            new Response(200, [], $response),
        ]);

        $this->anonymousRepository->list();

        $this->assertNull($this->anonymousRepository->firstPage());
        $this->assertNull($this->anonymousRepository->lastPage());
        $this->assertNull($this->anonymousRepository->nextPage());
        $this->assertNull($this->anonymousRepository->previousPage());
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
    }
}

<?php

namespace Evoliz\Client\Repository;

use Evoliz\Client\Config;
use Evoliz\Client\Exception\ConfigException;
use Evoliz\Client\Exception\ResourceException;
use Evoliz\Client\Model\ContactClient;
use Evoliz\Client\Model\Response\ContactClientResponse;

class ContactClientRepository extends BaseRepository
{
    /**
     * @param Config $config
     * @throws ConfigException|\Exception
     */
    public function __construct(Config $config)
    {
        parent::__construct($config);
    }

    /**
     * Create a new client contact with given data
     * @param ContactClient|ContactClientResponse $contactClient
     * @return ContactClientResponse|string
     * @throws ResourceException
     */
    public function create($contactClient)
    {
        if ($contactClient instanceof ContactClientResponse) {
            $contactClient = new ContactClient((array) $contactClient);
        }

        $response = $this->client->post('api/v1/contacts-clients', [
            'body' => json_encode($this->mapPayload($contactClient))
        ]);

        $responseBody = json_decode($response->getBody()->getContents(), true);

        if ($response->getStatusCode() !== 201) {
            $errorMessage = $responseBody['error'] . ' : ';
            if ($response->getStatusCode() === 400) {
                foreach ($responseBody['message'] as $error) {
                    $errorMessage .= '<br>' . $error[0];
                }
            } else {
                $errorMessage .= $responseBody['message'];
            }
            throw new ResourceException($errorMessage, $response->getStatusCode());
        }

        if ($this->defaultReturnType === 'OBJECT') {
            return new ContactClientResponse($responseBody);
        } else {
            return json_encode($responseBody);
        }
    }
}

<?php

namespace Evoliz\Client\Repository;

use Evoliz\Client\Config;
use Evoliz\Client\Exception\ConfigException;
use Evoliz\Client\Exception\ResourceException;
use Evoliz\Client\Model\ContactClient;

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
     * @param array $payload
     * @return ContactClient|false|string
     * @throws ResourceException
     */
    public function create(array $payload)
    {
        $response = $this->client->post('api/v1/contacts-clients', [
            'body' => json_encode($payload)
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
            return new ContactClient($responseBody);
        } else {
            return json_encode($responseBody);
        }
    }
}

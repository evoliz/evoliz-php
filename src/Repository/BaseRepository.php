<?php

namespace Evoliz\Client\Repository;

use Evoliz\Client\Config;

abstract class BaseRepository
{
    /**
     * @var Config
     */
    protected $config;

    /**
     * @throws \Exception
     */
    public function __construct(Config $config)
    {
        $this->config = $config->authenticate();
    }

    /**
     * Mapping of the request payload to create the entry
     * @param $object
     * @return array
     */
    protected function mapPayload($object): array
    {
        $payload = [];
        foreach ($object as $attribute => $value) {
            if (isset($object->$attribute)) {
                $payload[$attribute] = $value;
            }
        }
        return $payload;
    }
}

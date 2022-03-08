<?php

namespace Evoliz\Client\Model;

interface ModelInterface
{
    /**
     * Mapping of the request payload to create the entry
     * @return array
     */
    public function mapPayload(): array;
}

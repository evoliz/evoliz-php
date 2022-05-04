<?php

namespace Evoliz\Client\Response;

interface ResponseInterface
{
    public function toRequestBody(): array;
}

<?php

declare(strict_types=1);

namespace App\Services;

use App\Services\Exceptions\RequestFailedException;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class GetUserIpService
{
    public function __construct(
        private HttpClientInterface $client,
        private string $apiUrl
    ) {
    }

    public function getUserIp(): string
    {
        $response = $this->client->request(
            'GET',
            sprintf(
                '%s/plain',
                $this->apiUrl
            ),
        );

        if (200 !== $response->getStatusCode()) {
            throw RequestFailedException::badRequest($response);
        }

        return $response->getContent();
    }
}
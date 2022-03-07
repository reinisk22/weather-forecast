<?php

declare(strict_types=1);

namespace App\Services;

use App\Entity\IpLocation;
use App\Services\Exceptions\RequestFailedException;
use JetBrains\PhpStorm\ArrayShape;
use Symfony\Contracts\HttpClient\HttpClientInterface;

use function Safe\json_decode;

class GetIpLocationService
{
    public function __construct(
        private HttpClientInterface $client,
        private string $apiUrl,
        private string $secretKey
    ) {
    }

    #[ArrayShape([
        'ip'        => 'mixed',
        'zip'       => 'mixed',
        'latitude'  => 'mixed',
        'longitude' => 'mixed',
    ])]
    public function getIpLocation(string $ip): IpLocation
    {
        $response = $this->client->request(
            'GET',
            sprintf(
                '%s/%s?access_key=%s',
                $this->apiUrl,
                $ip,
                $this->secretKey
            ),
        );

        if (200 !== $response->getStatusCode()) {
            throw RequestFailedException::badRequest($response);
        }

        $result = json_decode($response->getContent(), true);

        return (new IpLocation())
            ->setIp($result['ip'])
            ->setZip($result['zip'])
            ->setLatitude($result['latitude'])
            ->setLongitude($result['longitude']);
    }
}
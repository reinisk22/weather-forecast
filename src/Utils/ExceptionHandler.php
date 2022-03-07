<?php

declare(strict_types=1);

namespace App\Utils;

use App\Services\Exceptions\RequestFailedException;
use RuntimeException;
use Symfony\Contracts\HttpClient\ResponseInterface;

class ExceptionHandler
{
    public static function handleError(ResponseInterface $response): void
    {
        throw match ($response->getStatusCode()) {
            403     => RequestFailedException::badRequest($response),
            404     => RequestFailedException::notFound($response),
            default => new RuntimeException($response->getContent(), $response->getStatusCode()),
        };
    }
}
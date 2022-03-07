<?php

declare(strict_types=1);

namespace App\Services\Exceptions;

use RuntimeException;
use Symfony\Contracts\HttpClient\ResponseInterface;

class RequestFailedException extends RuntimeException
{
    public static function badRequest(ResponseInterface $response): self
    {
        return new self(
            sprintf(
                'Bad Request. Message - %s',
                $response->getContent(),
            )
        );
    }

    public static function notFound(ResponseInterface $response): self
    {
        return new self(
            sprintf(
                'Not Found. Message - %s',
                $response->getContent(),
            )
        );
    }
}
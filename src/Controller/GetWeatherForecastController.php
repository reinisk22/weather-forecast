<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\IpLocationRepository;
use App\Services\GetIpLocationService;
use App\Services\GetUserIpService;
use App\Services\GetWeatherForecastService;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/weather-forecast', methods: ['GET'])]
class GetWeatherForecastController
{
    public function __construct(
        private GetIpLocationService $ipLocationService,
        private GetWeatherForecastService $weatherForecastService,
        private GetUserIpService $userIpService,
        private IpLocationRepository $ipLocationRepository
    ) {
    }

    public function __invoke(Request $request): Response
    {
        $cache = new FilesystemAdapter();
        $ip    = $cache->get('ip', function () {
           return $this->userIpService->getUserIp();
        });

        $ipLocation = $this->ipLocationRepository->ofIp($ip);
        if (null === $ipLocation || null !== $request->query->get('update_ip_location')) {
            $ipLocation = $this->ipLocationService->getIpLocation($ip);

            $this->ipLocationRepository->save($ipLocation);
        }

        $weatherForecast = $this->weatherForecastService->getWeatherForecast(
            $ipLocation->getLatitude(),
            $ipLocation->getLongitude()
        );

        return new JsonResponse($weatherForecast);
    }
}
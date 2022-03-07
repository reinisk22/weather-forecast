# Simple API to get weather forecast for your ip addresses location

# External API's used

### IP - https://ipecho.net/developers.html
> **Note:** Usually we would get ip from `Symfony\Component\HttpFoundation\Request::GetClientIp()`, 
> but because this is a sample app that will only be used in a local development environment,
>we would only get `127.0.0.1` or similar ips and that would defeat the applications purpose.

### IP Location - https://ipstack.com/

### Weather Forecast - https://openweathermap.org/

> **Important:** IP location and Weather forecast APIs require a secret key, please get your keys and add them to the following `.env` variables:
> - IP_LOCATION_SECRET_KEY
> - WEATHER_FORECAST_SECRET_KEY
> 
### Requirements

 * [Composer](https://getcomposer.org/download/)
 * [Symfony CLI](https://github.com/symfony-cli/symfony-cli)
 * [Docker](https://www.docker.com/products/docker-desktop)
 * PHP 8.0

### How to use

```
git clone https://github.com/reinisk22/weather-forecast.git
cd /weather-forecast
composer install

symfony serve -d

docker compose up -d

symfony console doctrine:migrations:migrate
```

### Endpoints

```
Description: Get current weather forecast
Method: GET
Route: /weather-forecast
    Query: update_ip_location
        Description: Add this query string to request to refresh the ip location data in database 
        Example: /weather-forecast?update_ip_location
```
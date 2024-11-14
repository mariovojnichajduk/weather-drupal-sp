<?php

namespace Drupal\current_weather\Service;

use GuzzleHttp\ClientInterface;
use Psr\Log\LoggerInterface;

class WeatherApiClient {
  protected ClientInterface $httpClient;
  protected LoggerInterface $logger;

  public function __construct(ClientInterface $httpClient, LoggerInterface $logger) {
    $this->httpClient = $httpClient;
    $this->logger = $logger;
  }

  public function getWeatherData(string $city): ?array {
    $apiKey = 'c29b7afe07e67c4c4c4c0e27f522c34c';
    $url = "https://api.openweathermap.org/data/2.5/weather?q=" . urlencode($city) . "&units=metric&appid=" . $apiKey;

    try {
        $response = $this->httpClient->request('GET', $url, ['verify' => false]);
        $data = json_decode($response->getBody()->getContents(), true);

        if (json_last_error() !== JSON_ERROR_NONE || !isset($data['main']['temp'], $data['weather'][0]['description'], $data['weather'][0]['icon'])) {
            throw new \RuntimeException('Unable to retireve data from API.');
        }

        $temp = $data['main']['temp'];
        $weather = $data['weather'][0]['description'];
        $weatherIcon = $data['weather'][0]['icon'];

        return [
            'temperature' => $temp . ' °C',
            'weather' => ucfirst($weather),
            'weather_icon' => ucfirst($weatherIcon)
        ];
    } catch (\Exception $e) {
        $this->logger->error('Weather API request failed: ' . $e->getMessage());
        return null;
    }
}

}

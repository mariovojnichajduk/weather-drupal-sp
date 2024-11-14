<?php

namespace Drupal\current_weather\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\current_weather\Service\WeatherApiClient;

/**
 * Provides a 'Weather' Block.
 *
 * @Block(
 *   id = "weather_block",
 *   admin_label = @Translation("Weather Block"),
 *   category = @Translation("Weather"),
 * )
 */
class WeatherBlock extends BlockBase implements ContainerFactoryPluginInterface {
  protected WeatherApiClient $weatherApiClient;

  public function __construct(array $configuration, $plugin_id, $plugin_definition, WeatherApiClient $weatherApiClient) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->weatherApiClient = $weatherApiClient;
  }

  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('current_weather.api_client')
    );
  }

  public function build(): array {
    
    $temperature = $weather = $weatherIcon = NULL;
    $city = 'Subotica';
    $apiData = $this -> weatherApiClient -> getWeatherData($city);

    if ($apiData === null) {
      return [
        '#markup' => 
        '<div>
          <h2>We were unable to load current weather data for '.$city.'</h2>
        </div>',
      ];
  }


    if ($apiData) {
      $temperature = $apiData['temperature'];
      $weather = $apiData['weather'];
      $weatherIcon = $apiData['weather_icon'];
    }

    $iconUrl = "https://openweathermap.org/img/wn/{$weatherIcon}@2x.png";

    return [
      '#markup' => 
      '<div>
        <img src="' . htmlspecialchars($iconUrl) . '" alt="weather icon" />
        <h2>Current temperature in '.$city.' is ' . $temperature . '.</h2>
        <h4>Current weather is ' . $weather . '.</h4>
      </div>',
    ];

  }

  public function getCacheMaxAge() {
    return 0;
  }
  
}

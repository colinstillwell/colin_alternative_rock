<?php

namespace Drupal\spotify_api;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Logger\LoggerChannelInterface;
use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\Core\State\StateInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\RequestException;

/**
 * Defines a service for interacting with the Spotify API.
 */
final class SpotifyApiService {
  use StringTranslationTrait;

  /**
   * The state system.
   */
  protected StateInterface $state;

  /**
   * The configuration object factory.
   */
  protected ConfigFactoryInterface $configFactory;

  /**
   * The logger channel.
   */
  protected LoggerChannelInterface $logger;

  /**
   * The messenger service.
   */
  protected MessengerInterface $messenger;

  /**
   * The client for sending HTTP requests.
   */
  protected ClientInterface $httpClient;

  /**
   * Constructs the Spotify API service.
   */
  public function __construct(
    StateInterface $state,
    ConfigFactoryInterface $configFactory,
    LoggerChannelFactoryInterface $loggerFactory,
    MessengerInterface $messenger,
    ClientInterface $httpClient,
  ) {
    $this->state = $state;
    $this->configFactory = $configFactory;
    $this->logger = $loggerFactory->get('spotify_api');
    $this->messenger = $messenger;
    $this->httpClient = $httpClient;
  }

  /**
   * Sends an HTTP request and returns decoded JSON response.
   */
  protected function request(string $method, string $url, array $options = []): array {
    try {
      $response = $this->httpClient->request($method, $url, $options);
      $data = json_decode($response->getBody()->getContents(), TRUE);

      if (json_last_error() !== JSON_ERROR_NONE) {
        throw new \RuntimeException('JSON decoding error: ' . json_last_error_msg());
      }

      return $data;
    }
    catch (RequestException $e) {
      $status = $e->getResponse() ? $e->getResponse()->getStatusCode() : 'N/A';
      throw new \RuntimeException("HTTP {$method} request to {$url} failed. Status: {$status}. Error: " . $e->getMessage());
    }
  }

  /**
   * Gets the access token for the Spotify API.
   */
  protected function getAccessToken(): string {
    $token_data = $this->state->get('spotify_api.access_token', []);

    // Check if the token exists and has not expired.
    if (!empty($token_data['access_token']) && $token_data['expires'] > time()) {
      return $token_data['access_token'];
    }

    // No valid token, attempt to get a new one.
    return $this->getNewAccessToken();
  }

  /**
   * Gets a new access token for the Spotify API.
   */
  protected function getNewAccessToken(): string {
    $config = $this->configFactory->get('spotify_api.settings');
    $client_id = $config->get('client_id');
    $client_secret = $config->get('client_secret');

    if (empty($client_id) || empty($client_secret)) {
      throw new \RuntimeException('Spotify API credentials are missing.');
    }

    $options = [
      'form_params' => ['grant_type' => 'client_credentials'],
      'headers' => [
        'Authorization' => 'Basic ' . base64_encode("{$client_id}:{$client_secret}"),
        'Content-Type' => 'application/x-www-form-urlencoded',
        'Accept' => 'application/json',
      ],
    ];

    $data = $this->request('POST', 'https://accounts.spotify.com/api/token', $options);

    if (empty($data['access_token']) || empty($data['expires_in'])) {
      throw new \RuntimeException('Spotify API token response missing expected keys.');
    }

    // Store token and set expiry buffer (30 sec).
    $expires_at = time() + $data['expires_in'] - 30;
    $this->state->set('spotify_api.access_token', [
      'access_token' => $data['access_token'],
      'expires' => $expires_at,
    ]);

    return $data['access_token'];
  }

  /**
   * Fetches artist data from the Spotify API.
   */
  public function fetchArtist(string $spotify_id): array {
    try {
      $access_token = $this->getAccessToken();
      $options = [
        'headers' => [
          'Authorization' => "Bearer {$access_token}",
          'Accept' => 'application/json',
        ],
      ];

      return $this->request('GET', "https://api.spotify.com/v1/artists/{$spotify_id}", $options);
    }
    catch (\RuntimeException $e) {
      // If credentials are missing, show an actionable message to the user.
      if (str_starts_with($e->getMessage(), 'Spotify API credentials are missing.')) {
        $this->messenger->addError($this->t('Spotify API credentials are missing. Please configure them at <a href=":url">Spotify API settings</a>.', [
          ':url' => '/admin/config/services/spotify-api',
        ]));
      }
      else {
        // Generic message for all other failures.
        $this->messenger->addError($this->t('There was an issue retrieving artist data from Spotify. Please try again later.'));
      }

      // Log all details for debugging.
      $this->logger->error('Spotify API error for artist ID @id: @message', [
        '@id' => $spotify_id,
        '@message' => $e->getMessage(),
      ]);
    }

    return [];
  }

}

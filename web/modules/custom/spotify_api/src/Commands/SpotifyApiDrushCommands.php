<?php

namespace Drupal\spotify_api\Commands;

use Drush\Attributes as CLI;
use Drush\Commands\DrushCommands;
use Drupal\spotify_api\Service\SpotifyApiService;

/**
 * Defines Drush commands for the Spotify API module.
 */
final class SpotifyApiDrushCommands extends DrushCommands {

  /**
   * The Spotify API service.
   *
   * @var \Drupal\spotify_api\Service\SpotifyApiService
   */
  protected SpotifyApiService $spotifyApiService;

  /**
   * Constructs a new instance of the command.
   *
   * @param \Drupal\spotify_api\Service\SpotifyApiService $spotifyApiService
   *   The Spotify API service.
   */
  public function __construct(SpotifyApiService $spotifyApiService) {
    parent::__construct();
    $this->spotifyApiService = $spotifyApiService;
  }

  /**
   * Updates Spotify API credentials.
   */
  #[CLI\Command(name: 'spotify-api:update-credentials', aliases: ['spotify-api:uc'])]
  #[CLI\Option(name: 'client-id', description: 'The Spotify API Client ID.')]
  #[CLI\Option(name: 'client-secret', description: 'The Spotify API Client Secret.')]
  #[CLI\Help(description: 'Update Spotify API credentials.', synopsis: 'drush spotify-api:update-credentials --client-id=abc123 --client-secret=def456')]
  public function updateCredentials(
    array $options = ['client-id' => '', 'client-secret' => ''],
  ): void {
    // Validate required options.
    if (empty($options['client-id']) || empty($options['client-secret'])) {
      throw new \Exception('Both --client-id and --client-secret options must be provided.');
    }

    // Call the service to update credentials.
    $this->spotifyApiService->updateCredentials($options['client-id'], $options['client-secret']);
  }

}

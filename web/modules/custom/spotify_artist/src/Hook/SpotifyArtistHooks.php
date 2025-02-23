<?php

namespace Drupal\spotify_artist\Hook;

use Drupal\Core\Hook\Attribute\Hook;

/**
 * Hook implementations for spotify_artist.
 */
class SpotifyArtistHooks {

  /**
   * Implements hook_theme().
   */
  #[Hook('theme')]
  public function theme(): array {
    return [
      'spotify_artist' => [
        'render element' => 'elements',
        'template' => 'spotify-artist',
      ],
    ];
  }

}

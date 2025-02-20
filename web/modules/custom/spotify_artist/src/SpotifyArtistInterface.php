<?php

namespace Drupal\spotify_artist;

use Drupal\Core\Entity\ContentEntityInterface;

/**
 * Defines an interface for Spotify Artist entities.
 */
interface SpotifyArtistInterface extends ContentEntityInterface {

  /**
   * Gets the Spotify Artist id.
   *
   * @return string
   *   Id of the Spotify Artist.
   */
  public function getSpotifyId();

}

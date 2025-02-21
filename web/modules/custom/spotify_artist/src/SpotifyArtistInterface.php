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

  /**
   * Gets the Spotify Artist name.
   *
   * @return string
   *   Name of the Spotify Artist.
   */
  public function getArtistName();

  /**
   * Gets the Spotify Artist image.
   *
   * @return string
   *   Image URL of the Spotify Artist.
   */
  public function getArtistImage();

  /**
   * Gets the rendered Spotify Artist image.
   *
   * @param string $image_style
   *   The image style to render the image with.
   *
   * @return string
   *   Rendered image HTML.
   */
  public function getRenderedArtistImage($image_style);

}

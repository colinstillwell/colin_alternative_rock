<?php

namespace Drupal\spotify_artist;

use Drupal\Core\Entity\ContentEntityInterface;

/**
 * Defines an interface for Spotify Artist entities.
 */
interface SpotifyArtistInterface extends ContentEntityInterface {

  /**
   * Gets the Spotify Artist ID.
   *
   * @return string
   *   The unique Spotify ID of the artist.
   */
  public function getSpotifyId(): string;

  /**
   * Gets the Spotify Artist name.
   *
   * @return string
   *   The name of the Spotify Artist.
   */
  public function getArtistName(): string;

  /**
   * Gets the Spotify Artist image.
   *
   * @param string $image_style
   *   The image style to use.
   *
   * @return string
   *   The image URL of the Spotify Artist.
   */
  public function getArtistImage(string $image_style): string;

  /**
   * Gets the Spotify Artist genres.
   *
   * @return string
   *   A comma-separated string of genres associated with the artist.
   */
  public function getArtistGenres(): string;

  /**
   * Gets the Spotify Artist followers count.
   *
   * @return int
   *   The number of followers the Spotify Artist has.
   */
  public function getArtistFollowers(): int;

  /**
   * Gets the Spotify Artist URL.
   *
   * @return string
   *   The direct Spotify URL for this artist.
   */
  public function getSpotifyUrl(): string;

  /**
   * Gets the Spotify Artist popularity score.
   *
   * @return int
   *   The popularity of the Spotify Artist (0-100).
   */
  public function getArtistPopularity(): int;

}

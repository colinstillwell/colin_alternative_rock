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
   * @param string $image_style
   *   The image style to use.
   *
   * @return string
   *   Image of the Spotify Artist.
   */
  public function getArtistImage($image_style);

  /**
   * Gets the Spotify Artist genres.
   *
   * @return string
   *   Genres of the Spotify Artist.
   */
  public function getArtistGenres();

  /**
   * Gets the Spotify Artist followers.
   *
   * @return int
   *   Number of followers of the Spotify Artist.
   */
  public function getArtistFollowers();

  /**
   * Gets the Spotify Artist url.
   *
   * @return string
   *   URL of the Spotify Artist.
   */
  public function getSpotifyUrl();

  /**
   * Gets the Spotify Artist popularity.
   *
   * @return int
   *   Popularity of the Spotify Artist.
   */
  public function getArtistPopularity();

}

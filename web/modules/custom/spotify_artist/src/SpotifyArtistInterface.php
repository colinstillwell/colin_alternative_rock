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
   * Gets a renderable array of the Spotify Artist image.
   *
   * @param string $image_style
   *   The image style to render the image with.
   *
   * @return array<string, mixed>
   *   Renderable array of the Spotify Artist image.
   */
  public function getRenderableArtistImage($image_style): array;

  /**
   * Gets a rendered string of the Spotify Artist image.
   *
   * @param string $image_style
   *   The image style to render the image with.
   *
   * @return string
   *   Rendered string of the Spotify Artist image.
   */
  public function getRenderedArtistImage($image_style): string;

  /**
   * Gets the Spotify Artist genres.
   *
   * @param bool $as_string
   *   If TRUE, return a string. If FALSE, return an array.
   *
   * @return array|string
   *   Genres of the Spotify Artist.
   */
  public function getArtistGenres(bool $as_string = FALSE);

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

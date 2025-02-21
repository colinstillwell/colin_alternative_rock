<?php

namespace Drupal\spotify_artist;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;

/**
 * Defines a class to build a listing of Spotify Artist entities.
 */
class SpotifyArtistListBuilder extends EntityListBuilder {

  /**
   * {@inheritdoc}
   */
  public function render() {
    $build['table'] = parent::render();

    // Add a summary of the total number of Spotify Artists, for convenience.
    $total = $this->getStorage()
      ->getQuery()
      ->count()
      ->accessCheck(TRUE)
      ->execute();

    $build['summary']['#markup'] = $this->t('Total Spotify Artists: @total', ['@total' => $total]);

    return $build;
  }

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['artist_image'] = $this->t('Image');
    $header['artist_name'] = $this->t('Name');
    $header['page_title'] = $this->t('Page Title');
    $header['artist_genres'] = $this->t('Genres');
    $header['artist_poularity'] = $this->t('Popularity');
    $header['artist_followers'] = $this->t('Followers');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /** @var \Drupal\spotify_artist\SpotifyArtistInterface $entity */
    $row['artist_image'] = $entity->getRenderedArtistImage('thumbnail');
    $row['artist_name'] = $entity->getArtistName();
    $row['page_title'] = $entity->toLink();
    $row['artist_genres'] = $entity->getArtistGenres(TRUE);
    $row['artist_poularity'] = $entity->getArtistPopularity();
    $row['artist_followers'] = $entity->getArtistFollowers();

    return $row + parent::buildRow($entity);
  }

}

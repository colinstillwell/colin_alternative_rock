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
    $header['id'] = $this->t('ID');
    $header['page_title'] = $this->t('Page Title');
    $header['spotify_id'] = $this->t('Spotify ID');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /** @var \Drupal\spotify_artist\SpotifyArtistInterface $entity */
    $row['id'] = $entity->id();
    $row['page_title'] = $entity->toLink();
    $row['spotify_id'] = $entity->getSpotifyId();

    return $row + parent::buildRow($entity);
  }

}

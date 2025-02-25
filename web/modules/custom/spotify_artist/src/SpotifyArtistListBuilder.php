<?php

namespace Drupal\spotify_artist;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Render\Markup;

/**
 * Defines a class to build a listing of Spotify Artist entities.
 */
class SpotifyArtistListBuilder extends EntityListBuilder {

  /**
   * {@inheritdoc}
   */
  public function render(): array {
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
  public function load(): array {
    $query = $this->getStorage()->getQuery()
      ->accessCheck(TRUE)
      ->sort('artist_popularity', 'DESC')
      ->sort('artist_followers', 'DESC')
      ->sort('artist_name', 'ASC')
      ->execute();

    return $this->storage->loadMultiple($query);
  }

  /**
   * {@inheritdoc}
   */
  public function buildHeader(): array {
    $header['artist_image'] = $this->t('Image');
    $header['artist_name'] = $this->t('Name');
    $header['page_title'] = $this->t('Page Title');
    $header['artist_genres'] = $this->t('Genres');
    $header['artist_popularity'] = $this->t('Popularity');
    $header['artist_followers'] = $this->t('Followers');
    $header['created'] = $this->t('Created');
    $header['changed'] = $this->t('Updated');

    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity): array {
    /** @var \Drupal\spotify_artist\Entity\SpotifyArtistInterface $entity */
    $row['artist_image'] = Markup::create($entity->getRenderedArtistImage('spotify_artist_80'));
    $row['artist_name'] = $entity->getArtistName();
    $row['page_title'] = $entity->toLink();
    $row['artist_genres'] = $entity->getArtistGenres();
    $row['artist_popularity'] = $entity->getArtistPopularity();
    $row['artist_followers'] = $entity->getArtistFollowers();
    $row['created'] = $entity->getCreated();
    $row['changed'] = $entity->getChanged();

    return $row + parent::buildRow($entity);
  }

}

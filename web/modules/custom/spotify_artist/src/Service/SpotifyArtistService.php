<?php

namespace Drupal\spotify_artist\Service;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Logger\LoggerChannelInterface;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;

/**
 * Defines a service for interacting with Spotify Artist entities.
 */
final class SpotifyArtistService {
  use StringTranslationTrait;

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected EntityTypeManagerInterface $entityTypeManager;

  /**
   * The logger channel.
   *
   * @var \Drupal\Core\Logger\LoggerChannelInterface
   */
  protected LoggerChannelInterface $logger;

  /**
   * The messenger service.
   *
   * @var \Drupal\Core\Messenger\MessengerInterface
   */
  protected MessengerInterface $messenger;

  /**
   * Constructs the Spotify Artist service.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entityTypeManager
   *   The entity type manager.
   * @param \Drupal\Core\Logger\LoggerChannelInterface $logger
   *   The logger channel.
   * @param \Drupal\Core\Messenger\MessengerInterface $messenger
   *   The messenger service.
   */
  public function __construct(
    EntityTypeManagerInterface $entityTypeManager,
    LoggerChannelInterface $logger,
    MessengerInterface $messenger,
  ) {
    $this->entityTypeManager = $entityTypeManager;
    $this->logger = $logger;
    $this->messenger = $messenger;
  }

  /**
   * Creates a Spotify Artist entity.
   *
   * @param string $page_title
   *   The page title of the artist.
   * @param string $spotify_id
   *   The Spotify ID of the artist.
   */
  public function createArtist(string $page_title, string $spotify_id): void {
    $storage = $this->entityTypeManager->getStorage('spotify_artist');

    // Check if artist already exists.
    $existing_ids = $storage->getQuery()
      ->accessCheck(TRUE)
      ->condition('spotify_id', $spotify_id)
      ->execute();

    // Skip creating if artist already exists.
    if (!empty($existing_ids)) {
      $this->messenger->addWarning($this->t('Skipped creating Spotify Artist: @page_title (@spotify_id) because it already exists.', [
        '@page_title' => $page_title,
        '@spotify_id' => $spotify_id,
      ]));
      return;
    }

    // Create the entity.
    $values = [
      'page_title' => $page_title,
      'spotify_id' => $spotify_id,
    ];
    $spotify_artist = $storage->create($values);
    $spotify_artist->save();

    // Show success message.
    $this->messenger->addStatus($this->t('Created Spotify Artist: @page_title (@spotify_id).', [
      '@page_title' => $page_title,
      '@spotify_id' => $spotify_id,
    ]));
  }

}

<?php

/**
 * @file
 * Contains post update functions for the Spotify Artist module.
 */

use Drupal\Core\Field\FieldStorageDefinitionInterface;

/**
 * Add created and changed timestamps to all Spotify Artist entities.
 */
function spotify_artist_post_update_add_created_changed(): void {
  $entity_type_id = 'spotify_artist';
  $provider = 'spotify_artist';

  // Get new field definitions.
  $entity_field_manager = \Drupal::service('entity_field.manager');
  $base_field_definitions = $entity_field_manager->getBaseFieldDefinitions($entity_type_id);

  foreach (['created', 'changed'] as $field_name) {
    if (!isset($base_field_definitions[$field_name])) {
      continue;
    }

    // Install new field definitions.
    $field_storage_definition = $base_field_definitions[$field_name]->getFieldStorageDefinition();
    if ($field_storage_definition instanceof FieldStorageDefinitionInterface) {
      \Drupal::entityDefinitionUpdateManager()->installFieldStorageDefinition(
        $field_name,
        $entity_type_id,
        $provider,
        $field_storage_definition
      );
    }
  }

  // Check for existing entities.
  $storage = \Drupal::entityTypeManager()->getStorage($entity_type_id);
  $ids = $storage->getQuery()
    ->accessCheck(FALSE)
    ->execute();

  if (!$ids) {
    return;
  }

  // Update existing entities.
  $artists = $storage->loadMultiple($ids);
  $current_time = \Drupal::time()->getRequestTime();

  foreach ($artists as $artist) {
    /** @var \Drupal\spotify_artist\Entity\SpotifyArtistInterface $artist */
    $artist->set('created', $artist->get('created')->value ?? $current_time);
    $artist->set('changed', $artist->get('changed')->value ?? $current_time);
    $artist->save();
  }
}

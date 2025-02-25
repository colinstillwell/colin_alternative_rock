<?php

namespace Drupal\spotify_artist\Entity;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Entity\Attribute\ContentEntityType;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Entity\ContentEntityDeleteForm;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Entity\Routing\AdminHtmlRouteProvider;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\spotify_artist\SpotifyArtistListBuilder;
use Drupal\spotify_artist\Form\SpotifyArtistAddForm;
use Drupal\spotify_artist\Form\SpotifyArtistEditForm;

/**
 * Defines the Spotify Artist entity.
 */
#[ContentEntityType(
  id: 'spotify_artist',
  label: new TranslatableMarkup('Spotify Artist'),
  label_collection: new TranslatableMarkup('Spotify Artists'),
  label_singular: new TranslatableMarkup('Spotify Artist'),
  label_plural: new TranslatableMarkup('Spotify Artists'),
  label_count: [
    'singular' => '@count Spotify Artist',
    'plural' => '@count Spotify Artists',
  ],
  entity_keys: [
    'id' => 'id',
    'uuid' => 'uuid',
    'label' => 'page_title',
  ],
  handlers: [
    'list_builder' => SpotifyArtistListBuilder::class,
    'form' => [
      'default' => ContentEntityForm::class,
      'add' => SpotifyArtistAddForm::class,
      'edit' => SpotifyArtistEditForm::class,
      'delete' => ContentEntityDeleteForm::class,
    ],
    'route_provider' => [
      'html' => AdminHtmlRouteProvider::class,
    ],
  ],
  links: [
    'canonical' => '/spotify-artist/{spotify_artist}',
    'add-form' => '/admin/content/spotify-artists/add',
    'edit-form' => '/admin/content/spotify-artists/{spotify_artist}/edit',
    'delete-form' => '/admin/content/spotify-artists/{spotify_artist}/delete',
    'collection' => '/admin/content/spotify-artists',
  ],
  admin_permission: 'administer spotify artist',
  base_table: 'spotify_artist',
  data_table: 'spotify_artist_field_data',
)]
class SpotifyArtist extends ContentEntityBase {
  use StringTranslationTrait;

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type): array {
    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['page_title'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Page Title'))
      ->setDescription(t('The title for the Spotify Artist page.'))
      ->setRequired(TRUE)
      ->setSettings([
        'max_length' => 255,
        'default_value' => NULL,
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => 0,
      ])
      ->setDisplayConfigurable('form', FALSE)
      ->setDisplayConfigurable('view', FALSE);

    $fields['spotify_id'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Spotify ID'))
      ->setDescription(t('The unique Spotify ID used for API integration. Not visible to users.'))
      ->setRequired(TRUE)
      ->setSettings([
        'max_length' => 22,
        'default_value' => NULL,
      ])
      ->addConstraint('UniqueField')
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => 1,
      ])
      ->setDisplayConfigurable('form', FALSE)
      ->setDisplayConfigurable('view', FALSE);

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created'))
      ->setDescription(t('The time that the Spotify Artist was created. Not visible to users.'))
      ->setRequired(TRUE)
      ->setDisplayOptions('form', [
        'weight' => 2,
      ])
      ->setDisplayConfigurable('form', FALSE)
      ->setDisplayConfigurable('view', FALSE);

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time that the Spotify Artist was last changed.'))
      ->setRequired(TRUE)
      ->setDisplayConfigurable('form', FALSE)
      ->setDisplayConfigurable('view', FALSE);

    $fields['artist_name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Artist Name'))
      ->setDescription(t('The name of the artist. Retrieved automatically from Spotify.'))
      ->setRequired(FALSE)
      ->setReadOnly(TRUE)
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => 2,
      ])
      ->setDisplayConfigurable('form', FALSE)
      ->setDisplayConfigurable('view', FALSE);

    $fields['artist_image'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Artist Image'))
      ->setDescription(t('The image of the artist. Retrieved automatically from Spotify.'))
      ->setRequired(FALSE)
      ->setReadOnly(TRUE)
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => 3,
      ])
      ->setDisplayConfigurable('form', FALSE)
      ->setDisplayConfigurable('view', FALSE);

    $fields['artist_genres'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Artist Genres'))
      ->setDescription(t('A list of genres associated with the artist. Retrieved automatically from Spotify.'))
      ->setRequired(FALSE)
      ->setReadOnly(TRUE)
      ->setCardinality(FieldStorageDefinitionInterface::CARDINALITY_UNLIMITED)
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => 4,
      ])
      ->setDisplayConfigurable('form', FALSE)
      ->setDisplayConfigurable('view', FALSE);

    $fields['artist_followers'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Artist Followers'))
      ->setDescription(t('The number of followers the artist has. Retrieved automatically from Spotify.'))
      ->setRequired(FALSE)
      ->setReadOnly(TRUE)
      ->setSettings([
        'unsigned' => TRUE,
        'size' => 'big',
        'default_value' => 0,
      ])
      ->setDisplayOptions('form', [
        'type' => 'number',
        'weight' => 5,
      ])
      ->setDisplayConfigurable('form', FALSE)
      ->setDisplayConfigurable('view', FALSE);

    $fields['spotify_url'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Spotify URL'))
      ->setDescription(t('The direct Spotify URL for this artist. Retrieved automatically from Spotify.'))
      ->setRequired(FALSE)
      ->setReadOnly(TRUE)
      ->setSettings([
        'max_length' => 255,
        'default_value' => NULL,
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => 6,
      ])
      ->setDisplayConfigurable('form', FALSE)
      ->setDisplayConfigurable('view', FALSE);

    $fields['artist_popularity'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Artist Popularity'))
      ->setDescription(t('The popularity of the artist. Retrieved automatically from Spotify.'))
      ->setRequired(FALSE)
      ->setReadOnly(TRUE)
      ->setSettings([
        'min' => 0,
        'max' => 100,
        'default_value' => NULL,
      ])
      ->setDisplayOptions('form', [
        'type' => 'number',
        'weight' => 7,
      ])
      ->setDisplayConfigurable('form', FALSE)
      ->setDisplayConfigurable('view', FALSE);

    return $fields;
  }

  /**
   * {@inheritdoc}
   */
  public function access($operation, ?AccountInterface $account = NULL, $return_as_object = FALSE) {
    $result = match ($operation) {
      'view' => AccessResult::allowedIf(!empty($account) && $account->hasPermission('view spotify artist entities'))
        ->cachePerPermissions(),
      default => parent::access($operation, $account, TRUE),
    };

    return $return_as_object ? $result : $result->isAllowed();
  }

  /**
   * {@inheritdoc}
   */
  public function getSpotifyId(): string {
    if (!$this->hasField('spotify_id')) {
      return '';
    }

    $value = $this->get('spotify_id')->value;
    return is_string($value) ? $value : '';
  }

  /**
   * {@inheritdoc}
   */
  public function getCreated(): int {
    if (!$this->hasField('created')) {
      return 0;
    }

    $value = $this->get('created')->value;
    return is_numeric($value) ? (int) $value : 0;
  }

  /**
   * {@inheritdoc}
   */
  public function getChanged(): int {
    if (!$this->hasField('changed')) {
      return 0;
    }

    $value = $this->get('changed')->value;
    return is_numeric($value) ? (int) $value : 0;
  }

  /**
   * {@inheritdoc}
   */
  public function getArtistName(): string {
    if (!$this->hasField('artist_name')) {
      return '';
    }

    $value = $this->get('artist_name')->value;
    return is_string($value) ? $value : '';
  }

  /**
   * {@inheritdoc}
   */
  public function getArtistImage(): string {
    if (!$this->hasField('artist_image')) {
      return '';
    }

    $value = $this->get('artist_image')->value;
    return is_string($value) ? $value : '';
  }

  /**
   * {@inheritdoc}
   */
  public function getArtistGenres(): array {
    if (!$this->hasField('artist_genres')) {
      return [];
    }

    $value = $this->get('artist_genres')->getValue();

    return is_array($value) ? array_column($value, 'value') : [];
  }

  /**
   * {@inheritdoc}
   */
  public function getArtistFollowers(): int {
    if (!$this->hasField('artist_followers')) {
      return 0;
    }

    $value = $this->get('artist_followers')->value;
    return is_numeric($value) ? (int) $value : 0;
  }

  /**
   * {@inheritdoc}
   */
  public function getSpotifyUrl(): string {
    if (!$this->hasField('spotify_url')) {
      return '';
    }

    $value = $this->get('spotify_url')->value;
    return is_string($value) ? $value : '';
  }

  /**
   * {@inheritdoc}
   */
  public function getArtistPopularity(): int {
    if (!$this->hasField('artist_popularity')) {
      return 0;
    }

    $value = $this->get('artist_popularity')->value;
    return is_numeric($value) ? (int) $value : 0;
  }

  /**
   * Updates a field with data fetched from Spotify.
   *
   * @param string $field_name
   *   The field name to update.
   * @param array|string $field_value
   *   The new value for the field.
   * @param bool $optional
   *   Whether the field update is optional.
   */
  protected function updateFromSpotify(string $field_name, array|string $field_value, bool $optional = FALSE): void {
    $field_definitions = $this->getFieldDefinitions();
    $field_label = $field_definitions[$field_name]->getLabel();
    $field_label = $field_label instanceof TranslatableMarkup ? $field_label->render() : (string) $field_label;

    if (!empty($field_value) && $this->hasField($field_name)) {
      $this->set($field_name, is_array($field_value) ? array_values($field_value) : (string) $field_value);
    }
    elseif ($optional) {
      \Drupal::messenger()->addWarning($this->t('No @label found from Spotify.', ['@label' => $field_label]));
    }
    else {
      \Drupal::messenger()->addError($this->t('Failed to update @label from Spotify.', ['@label' => $field_label]));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function preSave(EntityStorageInterface $storage): void {
    parent::preSave($storage);

    // Set created and changed timestamps.
    $current_time = \Drupal::time()->getRequestTime();
    if ($this->isNew() && $this->hasField('created') && !$this->get('created')->value) {
      $this->set('created', $current_time);
    }
    if ($this->hasField('changed')) {
      $this->set('changed', $current_time);
    }

    $spotify_id = $this->getSpotifyId();
    if (!empty($spotify_id)) {
      // Fetch artist data from Spotify.
      $spotify_api_service = \Drupal::service('spotify_api.service');
      $artist_data = $spotify_api_service->fetchArtist($spotify_id);

      // Apply updates.
      $this->updateFromSpotify('artist_name', $artist_data['name'] ?? '');
      $this->updateFromSpotify('artist_image', !empty($artist_data['images']) ? $artist_data['images'][0]['url'] : '');
      $this->updateFromSpotify('artist_genres', $artist_data['genres'] ?? [], TRUE);
      $this->updateFromSpotify('artist_followers', $artist_data['followers']['total'] ?? 0);
      $this->updateFromSpotify('spotify_url', $artist_data['external_urls']['spotify'] ?? '');
      $this->updateFromSpotify('artist_popularity', $artist_data['popularity'] ?? 0);
    }
  }

}

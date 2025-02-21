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
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'string',
        'weight' => 2,
      ])
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
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'string',
        'weight' => 3,
      ])
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
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'string',
        'weight' => 4,
      ])
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
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'number_integer',
        'weight' => 5,
      ])
      ->setDisplayConfigurable('view', FALSE);

    return $fields;
  }

  /**
   * {@inheritdoc}
   */
  public function access($operation, ?AccountInterface $account = NULL, $return_as_object = FALSE) {
    if ($operation === 'view') {
      $result = AccessResult::allowedIfHasPermission($account, 'view spotify artist entities');
      return $return_as_object ? $result : $result->isAllowed();
    }
    return parent::access($operation, $account, $return_as_object);
  }

  /**
   * {@inheritdoc}
   */
  public function getSpotifyId() {
    return $this->get('spotify_id')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function getArtistName() {
    return $this->get('artist_name')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function getArtistImage() {
    return $this->get('artist_image')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function getArtistGenres(bool $as_string = FALSE): array|string {
    $genres = $this->get('artist_genres')->getValue();
    $genre_list = array_map(fn($genre) => $genre['value'], $genres);
    return $as_string ? implode(', ', $genre_list) : $genre_list;
  }

  /**
   * {@inheritdoc}
   */
  public function getArtistFollowers() {
    return (int) $this->get('artist_followers')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function getRenderedArtistImage(string $image_style) {
    $render_array = [
      '#theme' => 'imagecache_external',
      '#uri' => $this->getArtistImage(),
      '#style_name' => $image_style,
      '#alt' => $this->getArtistName(),
    ];

    return \Drupal::service('renderer')->render($render_array);
  }

  /**
   * Update a field from Spotify.
   *
   * @param string $field_name
   *   The target field name.
   * @param string $field_value
   *   The new field value.
   * @param bool $optional
   *   Whether the value is optional.
   */
  protected function updateFromSpotify($field_name, $field_value, $optional = FALSE) {
    $field_definitions = $this->getFieldDefinitions();
    $field_label = $field_definitions[$field_name]->getLabel()->__toString();

    if (!empty($field_value)) {
      if (is_array($field_value)) {
        $this->set($field_name, array_values($field_value));
      }
      else {
        $this->set($field_name, $field_value);
      }
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
  public function preSave(EntityStorageInterface $storage) {
    parent::preSave($storage);

    $spotify_id = $this->getSpotifyId();
    if (!empty($spotify_id)) {
      // Fetch the artist data from the Spotify API.
      $spotify_api_service = \Drupal::service('spotify_api.service');
      $artist_data = $spotify_api_service->fetchArtist($spotify_id);

      // Update artist name from Spotify.
      $artist_name = $artist_data['name'] ?? '';
      $this->updateFromSpotify('artist_name', $artist_name);

      // Update artist image from Spotify.
      $artist_image = !empty($artist_data['images']) ? $artist_data['images'][0]['url'] : '';
      $this->updateFromSpotify('artist_image', $artist_image);

      // Update artist genres from Spotify.
      $artist_genres = $artist_data['genres'] ?? [];
      $this->updateFromSpotify('artist_genres', $artist_genres, TRUE);

      // Update artist followers from Spotify.
      $artist_followers = $artist_data['followers']['total'] ?? 0;
      $this->updateFromSpotify('artist_followers', $artist_followers);

    }
  }

}

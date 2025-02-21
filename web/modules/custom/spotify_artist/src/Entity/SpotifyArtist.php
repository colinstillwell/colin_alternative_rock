<?php

namespace Drupal\spotify_artist\Entity;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Entity\Attribute\ContentEntityType;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\ContentEntityDeleteForm;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Entity\Routing\AdminHtmlRouteProvider;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\spotify_artist\SpotifyArtistListBuilder;
use Drupal\spotify_artist\Form\SpotifyArtistForm;

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
      'default' => SpotifyArtistForm::class,
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
  public function preSave(EntityStorageInterface $storage) {
    parent::preSave($storage);

    $spotify_id = $this->getSpotifyId();
    if (!empty($spotify_id)) {
      // Fetch the artist data from the Spotify API.
      $spotify_api_service = \Drupal::service('spotify_api.service');
      $artist_data = $spotify_api_service->fetchArtist($spotify_id);

      // Set the artist name from the Spotify API response.
      if (!empty($artist_data['name'])) {
        $this->set('artist_name', $artist_data['name']);
        \Drupal::messenger()->addStatus($this->t('Successfully set artist name from Spotify.'));
      }
      else {
        \Drupal::messenger()->addError($this->t('Failed to set artist name from Spotify.'));
      }
    }
  }

}

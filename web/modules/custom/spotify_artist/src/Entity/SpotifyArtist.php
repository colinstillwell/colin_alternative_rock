<?php

namespace Drupal\spotify_artist\Entity;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Entity\Attribute\ContentEntityType;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\ContentEntityDeleteForm;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Entity\Routing\AdminHtmlRouteProvider;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\StringTranslation\TranslatableMarkup;
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
    'label' => 'title',
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

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type): array {
    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['title'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Admin Title'))
      ->setDescription(t('A title used only in the admin interface to help identify this Spotify Artist entity. Not visible to users.'))
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

    return $fields;
  }

  /**
   * {@inheritdoc}
   */
  public function access($operation, ?AccountInterface $account = NULL, $return_as_object = FALSE) {
    if ($operation === 'view') {
      $result = AccessResult::allowedIfHasPermission($account, 'view spotify artists');
      return $return_as_object ? $result : $result->isAllowed();
    }
    return parent::access($operation, $account, $return_as_object);
  }

}

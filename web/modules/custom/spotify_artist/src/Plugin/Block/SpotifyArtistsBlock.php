<?php

namespace Drupal\spotify_artist\Plugin\Block;

use Drupal\Core\Block\Attribute\Block;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a 'Spotify Artists' block.
 */
#[Block(
  id: 'spotify_artists',
  admin_label: new TranslatableMarkup('Spotify Artists'),
  category: new TranslatableMarkup('Spotify Showcase')
)]
class SpotifyArtistsBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected EntityTypeManagerInterface $entityTypeManager;

  /**
   * The current user.
   *
   * @var \Drupal\Core\Session\AccountInterface
   */
  protected AccountInterface $currentUser;

  /**
   * {@inheritdoc}
   */
  public function __construct($configuration, $plugin_id, $plugin_definition, EntityTypeManagerInterface $entityTypeManager, AccountInterface $currentUser) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->entityTypeManager = $entityTypeManager;
    $this->currentUser = $currentUser;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition): static {
    // Drupal core does this, so let's trust it for now.
    // @phpstan-ignore-next-line
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('entity_type.manager'),
      $container->get('current_user')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build(): array {
    // Load all artists.
    $storage = $this->entityTypeManager->getStorage('spotify_artist');
    $ids = $storage->getQuery()
      ->accessCheck(TRUE)
      ->sort('artist_popularity', 'DESC')
      ->sort('artist_followers', 'DESC')
      ->sort('artist_name', 'ASC')
      ->execute();
    $artists = $storage->loadMultiple($ids);
    $items = [];

    // Add each artist to the list of items to display.
    foreach ($artists as $artist) {
      /** @var \Drupal\spotify_artist\Entity\SpotifyArtistInterface $artist */
      $artist_name = $artist->getArtistName();

      // If the artist has no name, skip it.
      if (empty($artist_name)) {
        continue;
      }

      // If the current user has permission to view the artist, display the
      // artist name as a link to the artist page.
      if ($this->currentUser->hasPermission('view spotify artist entities')) {
        $items[] = $artist->toLink($artist_name);
      }
      // Otherwise, just display the artist name as plain text.
      else {
        $items[] = $artist_name;
      }
    }

    return [
      '#theme' => 'item_list',
      '#items' => $items,
      '#empty' => $this->t('None found.'),
      '#attached' => [
        'library' => [
          'spotify_artist/spotify_artists_block',
        ],
      ],
      '#attributes' => ['class' => ['spotify-artists-block']],
      '#cache' => [
        'contexts' => ['user.roles'],
        'tags' => ($definition = $this->entityTypeManager->getDefinition('spotify_artist')) ? $definition->getListCacheTags() : [],
      ],
    ];
  }

}

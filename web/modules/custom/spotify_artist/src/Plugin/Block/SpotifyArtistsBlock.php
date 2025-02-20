<?php

namespace Drupal\spotify_artist\Plugin\Block;

use Drupal\Core\Block\Attribute\Block;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\StringTranslation\TranslatableMarkup;

/**
 * Provides a 'Spotify Artists' block.
 */
#[Block(
  id: 'spotify_artists',
  admin_label: new TranslatableMarkup('Spotify Artists'),
  category: new TranslatableMarkup('Spotify Showcase')
)]
class SpotifyArtistsBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build(): array {
    return [
      // @todo Load the Spotify Artist entities and display them here.
      '#markup' => '<p>Spotify Artists</p>',
      '#cache' => [
        'contexts' => ['user.roles'],
      ],
    ];
  }

}

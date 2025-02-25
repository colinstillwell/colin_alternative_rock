<?php

namespace Drupal\spotify_artist\Commands;

use Drush\Attributes as CLI;
use Drush\Commands\DrushCommands;
use Drupal\spotify_artist\Service\SpotifyArtistService;

/**
 * Defines Drush commands for the Spotify Artist module.
 */
final class SpotifyArtistDrushCommands extends DrushCommands {

  /**
   * The Spotify Artist service.
   *
   * @var \Drupal\spotify_artist\Service\SpotifyArtistService
   */
  protected SpotifyArtistService $spotifyArtistService;

  /**
   * {@inheritdoc}
   *
   * @param \Drupal\spotify_artist\Service\SpotifyArtistService $spotifyArtistService
   *   The Spotify Artist service.
   */
  public function __construct(SpotifyArtistService $spotifyArtistService) {
    parent::__construct();
    $this->spotifyArtistService = $spotifyArtistService;
  }

  /**
   * Creates example Spotify Artists.
   */
  #[CLI\Command(name: 'spotify-artist:create-examples', aliases: ['spotify-artist:ce'])]
  #[CLI\Help(description: 'Creates example Spotify Examples from a predefined list.', synopsis: 'drush spotify-artist:create-examples')]
  public function createExamples(): void {
    $spotify_artists = [
      'Kaiser Chiefs' => '0LbLWjaweRbO4FDKYlbfNt',
      'The Killers' => '0C0XlULifJtAgn6ZNCW2eu',
      'Bloc Party' => '3MM8mtgFzaEJsqbjZBSsHJ',
      'Blur' => '7MhMgCo0Bl0Kukl93PZbYS',
      'Foo Fighters' => '7jy3rLJdDQY21OgRLCZ9sD',
      'Green Day' => '7oPftvlwr6VrsViSDV7fJY',
      'The Kooks' => '1GLtl8uqKmnyCWxHmw9tL4',
      'Oasis' => '2DaxqgrOhkeH0fpeiQq2f4',
      'Linkin Park' => '6XyY86QOPPrYVGvF9ch6wz',
      'Arctic Monkeys' => '7Ln80lUS6He07XvHI8qqHH',
      'Muse' => '12Chz98pHFMPJEknJQMWvI',
      'Razorlight' => '450iujbtN6XgiA9pv6fVZz',
      'Red Hot Chili Peppers' => '0L8ExT028jH3ddEcZwqJJ5',
      'Stereophonics' => '21UJ7PRWb3Etgsu99f8yo8',
      'blink-182' => '6FBDaR13swtiWwGhX1WQsP',
      'Fall Out Boy' => '4UXqAaa6dQYAk18Lv7PEgX',
      'Kasabian' => '11wRdbnoYqRddKBrpHt4Ue',
      'Biffy Clyro' => '1km0R7wy712AzLkA1WjKET',
      'Kings of Leon' => '2qk9voo8llSGYcZ6xrBzKx',
      'Travis' => '3bUwxJgNakzYKkqAVgZLlh',
    ];

    foreach ($spotify_artists as $page_title => $spotify_id) {
      $this->spotifyArtistService->createArtist($page_title, $spotify_id);
    }

  }

}

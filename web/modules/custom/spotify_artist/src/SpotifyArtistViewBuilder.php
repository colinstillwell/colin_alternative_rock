<?php

namespace Drupal\spotify_artist;

use Drupal\Core\Entity\EntityViewBuilder;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\Display\EntityViewDisplayInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Datetime\DateFormatterInterface;
use Drupal\Core\Language\LanguageManagerInterface;
use Drupal\Core\Theme\Registry;
use Drupal\Core\Entity\EntityDisplayRepositoryInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Entity\EntityRepositoryInterface;

/**
 * Defines the view builder for the Spotify Artist entity.
 */
class SpotifyArtistViewBuilder extends EntityViewBuilder {

  /**
   * The date formatter.
   *
   * @var \Drupal\Core\Datetime\DateFormatterInterface
   */
  protected DateFormatterInterface $dateFormatter;

  /**
   * {@inheritdoc}
   *
   * @param \Drupal\Core\Entity\EntityTypeInterface $entity_type
   *   The entity type definition.
   * @param \Drupal\Core\Entity\EntityRepositoryInterface $entity_repository
   *   The entity repository service.
   * @param \Drupal\Core\Language\LanguageManagerInterface $language_manager
   *   The language manager.
   * @param \Drupal\Core\Theme\Registry $theme_registry
   *   The theme registry.
   * @param \Drupal\Core\Entity\EntityDisplayRepositoryInterface $entity_display_repository
   *   The entity display repository.
   * @param \Drupal\Core\Datetime\DateFormatterInterface $date_formatter
   *   The date formatter.
   */
  public function __construct(EntityTypeInterface $entity_type, EntityRepositoryInterface $entity_repository, LanguageManagerInterface $language_manager, Registry $theme_registry, EntityDisplayRepositoryInterface $entity_display_repository, DateFormatterInterface $date_formatter) {
    parent::__construct($entity_type, $entity_repository, $language_manager, $theme_registry, $entity_display_repository);
    $this->dateFormatter = $date_formatter;
  }

  /**
   * {@inheritdoc}
   */
  public static function createInstance(ContainerInterface $container, EntityTypeInterface $entity_type): static {
    // Drupal core does this, so let's trust it for now.
    // @phpstan-ignore-next-line
    return new static(
      $entity_type,
      $container->get('entity.repository'),
      $container->get('language_manager'),
      $container->get('theme.registry'),
      $container->get('entity_display.repository'),
      $container->get('date.formatter')
    );
  }

  /**
   * {@inheritdoc}
   */
  protected function alterBuild(array &$build, EntityInterface $entity, EntityViewDisplayInterface $display, $view_mode): void {
    parent::alterBuild($build, $entity, $display, $view_mode);

    // Prepare template variables.
    /** @var \Drupal\spotify_artist\Entity\SpotifyArtist $entity */
    $build['artist_name'] = [
      '#plain_text' => $entity->getArtistName(),
    ];
    $build['artist_genres'] = [
      '#theme' => 'item_list',
      '#items' => $entity->getArtistGenres(),
      '#title' => $this->t('Genres'),
    ];
    $build['artist_followers'] = [
      '#type' => 'inline_template',
      '#template' => '<strong>{{ label }}</strong> {{ count }}',
      '#context' => [
        'label' => $this->t('Followers:'),
        'count' => number_format($entity->getArtistFollowers()),
      ],
    ];
    $build['artist_popularity'] = [
      '#type' => 'inline_template',
      '#template' => '<strong>{{ label }}</strong> {{ value }} / 100',
      '#context' => [
        'label' => $this->t('Popularity:'),
        'value' => $entity->getArtistPopularity(),
      ],
    ];
    $build['artist_image'] = [
      '#theme' => 'imagecache_external',
      '#uri' => $entity->getArtistImage(),
      '#style_name' => 'spotify_artist_160',
      '#alt' => $entity->getArtistName(),
    ];
    $build['artist_link'] = [
      '#type' => 'inline_template',
      '#template' => '<a href="{{ url }}" target="_blank" rel="noopener noreferrer">{{ label }}</a>',
      '#context' => [
        'url' => $entity->getSpotifyUrl(),
        'label' => $this->t('View on Spotify'),
      ],
    ];
    $build['last_updated'] = [
      '#type' => 'inline_template',
      '#template' => '<strong>{{ label }}</strong> {{ time_ago }} ago',
      '#context' => [
        'label' => $this->t('Last updated:'),
        'time_ago' => $this->dateFormatter->formatTimeDiffSince($entity->getChanged()),
      ],
    ];

    // Attach libraries.
    $build['#attached']['library'][] = 'spotify_artist/spotify_artist';
  }

}

<?php

namespace Drupal\spotify_artist\Form;

use Drupal\Component\Datetime\TimeInterface;
use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Entity\EntityRepositoryInterface;
use Drupal\Core\Entity\EntityTypeBundleInfoInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Render\RendererInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Form controller for editing a Spotify Artist entity.
 */
class SpotifyArtistEditForm extends ContentEntityForm {

  /**
   * The renderer.
   *
   * @var \Drupal\Core\Render\RendererInterface
   */
  protected RendererInterface $renderer;

  /**
   * {@inheritdoc}
   *
   * @param \Drupal\Core\Entity\EntityRepositoryInterface $entity_repository
   *   The entity repository service.
   * @param \Drupal\Core\Entity\EntityTypeBundleInfoInterface $entity_type_bundle_info
   *   The entity type bundle service.
   * @param \Drupal\Component\Datetime\TimeInterface $time
   *   The time service.
   * @param \Drupal\Core\Render\RendererInterface $renderer
   *   The renderer.
   */
  public function __construct(EntityRepositoryInterface $entity_repository, EntityTypeBundleInfoInterface $entity_type_bundle_info, TimeInterface $time, RendererInterface $renderer) {
    parent::__construct($entity_repository, $entity_type_bundle_info, $time);
    $this->renderer = $renderer;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container): static {
    // Drupal core does this, so let's trust it for now.
    // @phpstan-ignore-next-line
    return new static(
      $container->get('entity.repository'),
      $container->get('entity_type.bundle.info'),
      $container->get('datetime.time'),
      $container->get('renderer')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state): int {
    $result = parent::save($form, $form_state);

    // Show success message.
    $this->messenger()->addStatus($this->t('Updated Spotify Artist %page_title.', [
      '%page_title' => $this->entity->toLink()->toString(),
    ]));

    // Redirect to the entity view.
    $form_state->setRedirect('entity.spotify_artist.canonical', ['spotify_artist' => $this->entity->id()]);

    return $result;
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state): array {
    $form = parent::buildForm($form, $form_state);

    // Disable fields that should not be edited manually.
    $disabled_fields = [
      'created',
      'changed',
      'artist_name',
      'artist_image',
      'artist_genres',
      'artist_followers',
      'spotify_url',
      'artist_popularity',
    ];

    foreach ($disabled_fields as $field_name) {
      if (isset($form[$field_name])) {
        $form[$field_name]['#disabled'] = TRUE;
      }

      // Show image next to the artist_image field.
      if ($field_name === 'artist_image') {
        /** @var \Drupal\spotify_artist\Entity\SpotifyArtistInterface $entity */
        $entity = $this->entity;
        $image_render_array = [
          '#theme' => 'imagecache_external',
          '#uri' => $entity->getArtistImage(),
          '#style_name' => 'spotify_artist_160',
          '#alt' => $entity->getArtistName(),
        ];

        $form[$field_name]['#suffix'] = $this->renderer->render($image_render_array);
      }
    }

    return $form;
  }

}

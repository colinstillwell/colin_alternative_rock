<?php

namespace Drupal\spotify_artist\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form controller for editing a Spotify Artist entity.
 */
class SpotifyArtistEditForm extends ContentEntityForm {

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state): int {
    $result = parent::save($form, $form_state);

    // Show success message.
    $this->messenger()->addStatus($this->t("Updated Spotify Artist %page_title.", [
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
        $form[$field_name]['#suffix'] = $entity->getArtistImage('thumbnail');
      }
    }

    return $form;
  }

}

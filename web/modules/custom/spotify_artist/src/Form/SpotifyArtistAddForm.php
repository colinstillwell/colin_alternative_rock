<?php

namespace Drupal\spotify_artist\Form;

use Drupal\Core\Form\FormStateInterface;

/**
 * Form controller for the entity edit forms.
 */
class SpotifyArtistAddForm extends SpotifyArtistForm {

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $result = parent::save($form, $form_state);

    // Show success message.
    $this->messenger()->addStatus($this->t("Created Spotify Artist %page_title.", [
      '%page_title' => $this->entity->toLink()->toString(),
    ]));

    // Redirect to the entity list.
    $form_state->setRedirect('entity.spotify_artist.collection');

    return $result;
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form = parent::buildForm($form, $form_state);

    // Hide fields that should not be set manually.
    $form['artist_name']['#access'] = FALSE;

    return $form;
  }

}

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
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form = parent::buildForm($form, $form_state);

    // Hide fields that should not be set manually.
    $form['artist_name']['#access'] = FALSE;

    return $form;
  }

}

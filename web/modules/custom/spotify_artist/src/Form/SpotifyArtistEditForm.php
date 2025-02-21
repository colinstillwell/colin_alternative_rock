<?php

namespace Drupal\spotify_artist\Form;

use Drupal\Core\Form\FormStateInterface;

/**
 * Form controller for the entity edit forms.
 */
class SpotifyArtistEditForm extends SpotifyArtistForm {

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form = parent::buildForm($form, $form_state);

    // Disable fields that should not be edited manually.
    $form['artist_name']['#disabled'] = TRUE;

    return $form;
  }

}

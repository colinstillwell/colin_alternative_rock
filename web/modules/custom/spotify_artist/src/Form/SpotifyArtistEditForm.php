<?php

namespace Drupal\spotify_artist\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form controller for the entity edit forms.
 */
class SpotifyArtistEditForm extends ContentEntityForm {

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
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
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form = parent::buildForm($form, $form_state);

    // Disable fields that should not be edited manually.
    $form['artist_name']['#disabled'] = TRUE;

    return $form;
  }

}

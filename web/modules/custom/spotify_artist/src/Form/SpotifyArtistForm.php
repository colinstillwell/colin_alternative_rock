<?php

namespace Drupal\spotify_artist\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form controller for the entity edit forms.
 */
class SpotifyArtistForm extends ContentEntityForm {

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $result = parent::save($form, $form_state);
    $is_new = $result === SAVED_NEW;

    // Show success message.
    $this->messenger()->addStatus($this->t("%action Spotify Artist %label.", [
      '%action' => $is_new ? 'Created' : 'Updated',
      '%label' => $this->entity->toLink()->toString(),
    ]));

    // Redirect the user depending on whether the entity is new.
    if ($is_new) {
      // Redirect to the entity list.
      $form_state->setRedirect('entity.spotify_artist.collection');
    }
    else {
      // Redirect to the entity view.
      $form_state->setRedirect('entity.spotify_artist.canonical', ['spotify_artist' => $this->entity->id()]);
    }

    return $result;
  }

}

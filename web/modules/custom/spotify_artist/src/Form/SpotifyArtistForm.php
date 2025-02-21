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
    $this->messenger()->addStatus($this->t("%action Spotify Artist %page_title.", [
      '%action' => $is_new ? 'Created' : 'Updated',
      '%page_title' => $this->entity->toLink()->toString(),
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

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    $entity = parent::validateForm($form, $form_state);

    // Count existing Spotify Artist entities.
    $total = $this->entityTypeManager->getStorage('spotify_artist')
      ->getQuery()
      ->count()
      ->accessCheck(TRUE)
      ->execute();

    // Check if the limit (20) is exceeded.
    if ($total >= 20) {
      $form_state->setErrorByName('page_title', $this->t('You cannot create more than 20 Spotify Artists.'));
    }

    return $entity;
  }

}

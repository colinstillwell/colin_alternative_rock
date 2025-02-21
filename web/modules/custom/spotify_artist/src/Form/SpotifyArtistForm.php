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

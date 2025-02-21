<?php

namespace Drupal\spotify_artist\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form controller for the entity edit forms.
 */
class SpotifyArtistAddForm extends ContentEntityForm {

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

    // Count existing Spotify Artist entities.
    $total = $this->entityTypeManager->getStorage('spotify_artist')
      ->getQuery()
      ->count()
      ->accessCheck(TRUE)
      ->execute();

    // Check if the limit is exceeded.
    if ($total >= 20) {
      $this->messenger()->addError($this->t('You have reached the limit of 20 Spotify Artists.'));
      return [];
    }

    // Hide fields that should not be set manually.
    $hidden_fields = [
      'artist_name',
      'artist_image',
    ];

    foreach ($hidden_fields as $field_name) {
      if (isset($form[$field_name])) {
        $form[$field_name]['#access'] = FALSE;
      }
    }

    return $form;
  }

}

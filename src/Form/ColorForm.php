<?php

namespace Drupal\my_color_library\Form;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a form for adding/editing Color entities.
 */
class ColorForm extends EntityForm
{

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state)
  {
    $entity = $this->entity;

    $form['label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Label'),
      '#default_value' => $entity->label(),
      '#required' => TRUE,
    ];

    $form['hex_value'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Hex Value'),
      '#default_value' => $entity->getHexValue(),
      '#required' => TRUE,
      '#attributes' => [
        'class' => ['color-picker'], // Class for JS color picker
      ]
    ];

    $form['rgb_value'] = [
      '#type' => 'textfield',
      '#title' => $this->t('RGB Value'),
      '#default_value' => $entity->getRgbValue(),
      '#required' => FALSE,
    ];

    $form['description'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Description'),
      '#default_value' => $entity->getDescription(),
    ];

    $form['tags'] = [
      '#type' => 'textfield',  // Or 'entity_autocomplete' for managed tags
      '#title' => $this->t('Tags'),
      '#default_value' => $entity->getTags(),
      '#description' => $this->t('Comma-separated list of tags.'),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state)
  {
    parent::submitForm($form, $form_state);

    $entity = $this->entity;
    $this->save($entity, $form_state); // Important to pass form state
    $form_state->setRedirect('entity.color.collection'); // Redirect to list page
  }

  /**
   * {@inheritdoc}
   */
  public function save(EntityInterface|array $entity, FormStateInterface $form_state)
  {
    $entity->setHexValue(strtoupper($entity->getHexValue())); //Save hex value as uppercase.
    parent::save($entity, $form_state);
  }
}


//  get all the websafe colors
public function buildForm(array $form, FormStateInterface $form_state)
{
  $options = [];

  include_once drupal_get_path('module', 'color_library') . '/includes/websafe_colors.inc'; // Include the file

  $websafe_colors = json_decode(file_get_contents(drupal_get_path('module', 'color_library') . '/includes/websafe_colors.inc'));

  foreach ($websafe_colors as $hex => $name) {
    $options['websafe-' . $hex]['name'] = $name;
    $options['websafe-' . $hex]['hex_value'] = $hex;
    $options['websafe-' . $hex]['description'] = '216 Web Safe Named css3 Colors';
    $options['websafe-' . $hex]['tags'] = ['websafe', 'websafe colors', 'css3'];
  }

  // Query user-created colors (content entities):
  $user_colors = \Drupal::entityTypeManager()->getStorage('user_color')->loadAll();
  foreach ($user_colors as $color) {
    $options[$color->id()] = $color->label() . ' (' . $color->getHexValue() . ')';
  }

  // ... rest of your form code
}

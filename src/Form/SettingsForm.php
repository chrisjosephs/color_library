<?php

namespace Drupal\color_library\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Defines a form for configuring the color Library module.
 *
 * @internal
 *   Form classes are internal.
 */
class SettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getEditableConfigNames() {
    return ['color_library.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'color_library_settings_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    /*
     * @todo: remove the following probably:
     */
    $form['advanced_ui'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Enable advanced UI'),
      '#default_value' => $this->config('color_library.settings')->get('advanced_ui'),
      '#description' => $this->t('If checked, users creating new color items in the color library will see a summary of their selected color items, and they will be able to insert their selection directly into the color field or text editor.'),
    ];
    /**
     * @todo: Add some settings for the pallete icon
     */
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->config('color_library.settings')
      ->set('advanced_ui', (bool) $form_state->getValue('advanced_ui'))
      ->save();

    parent::submitForm($form, $form_state);
  }

}

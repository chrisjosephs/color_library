<?php

/**
 * @file
 * In your custom form element class:
 */

public function buildForm(array $form, FormStateInterface $form_state) {
  // ... (query both default and user colors as before)
  // Example search/filter (basic - improve as needed):
  // Get search term from a text field.
  $search_term = $form_state->getValue('tag_search');
  $filtered_options = [];
  foreach ($options as $key => $label) {
    // Split label to get color data.
    $color_data = explode('(', $label);
    $tags_string = '';
    if (strpos($key, 'default-') === 0) {
      $default_color_id = str_replace('default-', '', $key);
      $database = \Drupal::database();
      $color_data_db = $database->select('default_colors', 'dc')
        ->fields('dc')
        ->condition('id', $default_color_id)
        ->execute()
        ->fetchAssoc();
      $tags_string = $color_data_db['tags'];
    }
    else {
      $color = \Drupal::entityTypeManager()->getStorage('color')->load($key);
      $tags_string = $color->getTags();
    }

    if (empty($search_term) || strpos(strtolower($tags_string), strtolower($search_term)) !== FALSE) {
      $filtered_options[$key] = $label;
    }

  }

  $form['tag_search'] = [
    '#type' => 'textfield',
    '#title' => $this->t('Search by Tag'),
    '#default_value' => $search_term,
    '#ajax' => [
  // Callback to rebuild the select.
      'callback' => '::filterColorsCallback',
  // ID of the select element container.
      'wrapper' => 'color-select-wrapper',
  // Trigger on keyup.
      'event' => 'keyup',
      'progress' => [
        'type' => 'throbber',
        'message' => NULL,
      ],
    ],
  ];

  $form['color'] = [
    '#type' => 'select',
    '#title' => $this->t('Select a Color'),
  // Use the filtered options.
    '#options' => $filtered_options,
    '#default_value' => $this->defaultValue,
  // Wrapper for AJAX updates.
    '#prefix' => '<div id="color-select-wrapper">',
    '#suffix' => '</div>',
  ];

  return $form;
}

/**
 *
 */
public function filterColorsCallback(array &$form, FormStateInterface $form_state) {
  return $form['color'];
}


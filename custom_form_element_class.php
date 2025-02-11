<?php
// In your custom form element class:

public function buildForm(array $form, FormStateInterface $form_state) {
  // ... (query both default and user colors as before)

  // Example search/filter (basic - improve as needed):
  $search_term = $form_state->getValue('tag_search'); // Get search term from a text field
  $filtered_options = [];
  foreach ($options as $key => $label) {
    $color_data = explode('(', $label); // Split label to get color data
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
    } else {
      $color = \Drupal::entityTypeManager()->getStorage('color')->load($key);
      $tags_string = $color->getTags();
    }

    if (empty($search_term) || strpos(strtolower($tags_string), strtolower($search_term)) !== false) {
      $filtered_options[$key] = $label;
    }

  }

  $form['tag_search'] = [
    '#type' => 'textfield',
    '#title' => $this->t('Search by Tag'),
    '#default_value' => $search_term,
    '#ajax' => [
      'callback' => '::filterColorsCallback', // Callback to rebuild the select
      'wrapper' => 'color-select-wrapper', // ID of the select element container
      'event' => 'keyup', // Trigger on keyup
      'progress' => [
        'type' => 'throbber',
        'message' => NULL,
      ],
    ],
  ];

  $form['color'] = [
    '#type' => 'select',
    '#title' => $this->t('Select a Color'),
    '#options' => $filtered_options, // Use the filtered options
    '#default_value' => $this->defaultValue,
    '#prefix' => '<div id="color-select-wrapper">', // Wrapper for AJAX updates
    '#suffix' => '</div>',
  ];


  return $form;
}

public function filterColorsCallback(array &$form, FormStateInterface $form_state) {
  return $form['color'];
}

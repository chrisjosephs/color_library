<?php

namespace Drupal\color_library;

use Drupal\views\EntityViewsData;

/**
 * Provides the Views data for the color entity type.
 */
class ColorViewsData extends EntityViewsData {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();

    // Add any custom table joins or views-specific information.
    $data['color']['table']['group'] = t('Colors');
    $data['color']['table']['base'] = [
      'field' => 'cid',
      'title' => t('Color'),
      'help' => t('The Color entity ID.'),
    ];

    // Add the hexadecimal field as a filterable field in Views.
    $data['color']['hexadecimal'] = [
      'title' => t('Hex Value'),
      'help' => t('The hexadecimal value of the color.'),
      'field' => [
        'id' => 'standard',
      ],
      'filter' => [
        'id' => 'string',
      ],
    ];

    return $data;
  }

}

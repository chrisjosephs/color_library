<?php

namespace Drupal\my_color_library;

use Drupal\Core\Config\Entity\ConfigEntityListBuilder;
use Drupal\Core\Entity\EntityInterface;

/**
 * Provides a list controller for Color entity.
 *
 * @ingroup my_color_library
 */
class ColorListBuilder extends ConfigEntityListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    $row['label'] = $entity->label();
    $row['hex_value'] = $entity->getHexValue(); // Display the hex value
    // Add more columns as needed (e.g., description)
    return $row + parent::buildRow($entity);
  }

}

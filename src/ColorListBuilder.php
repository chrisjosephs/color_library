<?php

namespace Drupal\color_library;

use Drupal\Core\Config\Entity\ConfigEntityListBuilder;
use Drupal\Core\Entity\EntityInterface;

/**
 * Provides a list controller for Color entity.
 *
 * @ingroup color_library
 */
class ColorListBuilder extends ConfigEntityListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    $row['label'] = $entity->label();
    $row['hexadecimal'] = $entity->getHexValue();
    $row['description'] = $entity->getDescription();
    $row['id'] = $entity->id();
    $row['uuid'] = $entity->uuid();
    $row['status'] = $entity->status();
    $row['created'] = $entity->getCreatedTime();
    $row['changed'] = $entity->getChangedTime();

    // Add more columns as needed (e.g., description)
    return $row + parent::buildRow($entity);
  }

}

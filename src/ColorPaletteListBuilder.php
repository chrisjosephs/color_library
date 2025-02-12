<?php
/**
 *
 *
 *
 *
 *
 *
 *
 *
 * @todo: might not need this as it's just part of the color library really and
 * should just be factored into how that is viewed
 *
 *
 *
 *
 *
 *
 *
 *
 *
 *
 *
 *
 *
 */
namespace Drupal\my_color_library;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;

/**
 * Provides a list controller for ColorPalette entity.
 *
 * @ingroup my_color_library
 */
class ColorPaletteListBuilder extends EntityListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    $row['label'] = $entity->label(); // Use the entity label (name)
    // You might want to display some information about the associated colors here.
    return $row + parent::buildRow($entity);
  }

}

<?php

namespace Drupal\color_library\Entity;

use Drupal\color_library\Entity\ColorInterface;
use Drupal\Core\Entity\ContentEntityInterface;

/**
 * Provides an interface for defining ColorPalette entities.
 *
 * @ingroup color_library
 */
interface ColorPaletteInterface extends ContentEntityInterface {

  // Add getter and setter methods for your fields here if needed.
  public function getName();
  public function setName($name);

  public function getColors();
  public function addColor(ColorInterface $color);

  public function removeColor(ColorInterface $color);
  public function getColorByName($name);
  public function setColorByName($name, ColorInterface $color);
  public function getColorByHex($hex);
  public function setColorByHex($hex, ColorInterface $color);
  public function setColorIds(array $color_ids);
}

<?php

namespace Drupal\color_library\Entity;

use Drupal\Core\Entity\ContentEntityInterface;

/**
 * Provides an interface for defining Color entities.
 */
interface ColorInterface extends ContentEntityInterface {

  /**
   * Gets the Color hex value.
   *
   * @return string
   *   The Color hex value.
   */
  public function getHexValue();

  /**
   * Gets the Color RGB value.
   *
   * @return string
   *   The Color RGB value.
   */
  public function getRgbValue();

  /**
   * Gets the Color description.
   *
   * @return string
   *   The Color description.
   */
  public function getDescription();

  /**
   * Sets the Color hex value.
   *
   * @param string $hexadecimal
   *   The new Color hex value.
   *
   * @return $this
   */
  public function setHexValue($hexadecimal);


  /**
   * Find references to this color entity (where it has been referenced by entity, rather than the values copy/pasted etc)
   * @todo: also find references to the literal values??
   */
  public function whatUsesColor($id, ColorInterface $color);
}

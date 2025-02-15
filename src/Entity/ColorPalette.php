<?php

namespace Drupal\color_library\Entity;

use Drupal\Core\Entity\Attribute\ContentEntityType;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\StringTranslation\TranslatableMarkup;

/**
 * Defines the ColorPalette entity.
 *
 * @ingroup color_library
*/
#[ContentEntityType(
  id: "color_palette",
  label: new TranslatableMarkup("Color Palette"),
  entity_keys: [
    "id" => "id",
    "label" => "name",
    "uuid" => "uuid",
  ],
  handlers: [
    "view_builder" => "Drupal\Core\Entity\EntityViewBuilder",
    "list_builder" => "Drupal\my_color_library\ColorPaletteListBuilder",
    "form" => [
      "add" => "Drupal\my_color_library\Form\ColorPaletteForm",
      "edit" => "Drupal\my_color_library\Form\ColorPaletteForm",
      "delete" => "Drupal\my_color_library\Form\ColorPaletteDeleteForm",
    ],
    "route_provider" => [
      "html" => "Drupal\Core\Entity\Routing\AdminHtmlRouteProvider",
    ],
  ],
  links: [
    "canonical" => "/admin/structure/color_palettes/{color_palette}",
    "add-form" => "/admin/structure/color_palettes/add",
    "edit-form" => "/admin/structure/color_palettes/{color_palette}/edit",
    "delete-form" => "/admin/structure/color_palettes/{color_palette}/delete",
    "collection" => "/admin/structure/color_palettes",
  ],
  admin_permission: "administer color palette entities",
  base_table: 'color_palette',
  field_ui_base_route: "entity.color_palette.admin_form",
)]

class ColorPalette extends ContentEntityBase implements ColorPaletteInterface {

  /**
   *
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields['id'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('ID'))
      ->setReadOnly(TRUE)
      ->setSetting('unsigned', TRUE)
      ->setCardinality(1);

    $fields['uuid'] = BaseFieldDefinition::create('uuid')
      ->setLabel(t('UUID'))
      ->setReadOnly(TRUE)
      ->setCardinality(1);

    $fields['name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Name'))
      ->setRequired(TRUE)
      ->setSetting('max_length', 255);

    $fields['colors'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Colors'))
      ->setDescription(t('The colors in this palette.'))
      ->setSetting('target_type', 'color')
      ->setSetting('handler', 'default')
      ->setCardinality(FieldStorageDefinitionInterface::CARDINALITY_UNLIMITED);

    return $fields;
  }

  /**
   * Getters and setters (optional, but good practice):
   */
  public function getName() {
    return $this->get('name')->value;
  }

  /**
   *
   */
  public function setName($name) {
    $this->set('name', $name);
  }

  /**
   *
   */
  public function getColors() {
    // Returns an array of UserColor entities.
    return $this->get('colors')->referencedEntities();
  }

  /**
   *
   */
  public function setColorIds(array $color_ids) {
    $this->set('colors', $color_ids);
  }

  /**
   *
   */
  public function addColor(ColorInterface $color) {
    // @todo Implement addColor() method.
  }

  /**
   *
   */
  public function removeColor(ColorInterface $color) {
    // @todo Implement removeColor() method.
  }

  /**
   *
   */
  public function getColorByName($name) {
    // @todo Implement getColorByName() method.
  }

  /**
   *
   */
  public function setColorByName($name, ColorInterface $color) {
    // @todo Implement setColorByName() method.
  }

  /**
   *
   */
  public function getColorByHex($hex) {
    // @todo Implement getColorByHex() method.
  }

  /**
   *
   */
  public function setColorByHex($hex, ColorInterface $color) {
    // @todo Implement setColorByHex() method.
  }

}

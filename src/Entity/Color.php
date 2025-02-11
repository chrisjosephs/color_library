<?php

namespace Drupal\my_color_library\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBase;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;

/**
 * Defines the Color entity.
 *
 * @ConfigEntityType(
 *   id = "color",
 *   label = @Translation("Color"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\my_color_library\ColorListBuilder",
 *     "form" = {
 *       "add" = "Drupal\my_color_library\Form\ColorForm",
 *       "edit" = "Drupal\my_color_library\Form\ColorForm",
 *       "delete" = "Drupal\my_color_library\Form\ColorDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\Core\Entity\Routing\AdminHtmlRouteProvider",
 *     },
 *   },
 *   config_prefix = "color.color",
 *   admin_permission = "administer color entities", // Define your permission
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "canonical" = "/admin/structure/colors/{color}",
 *     "add-form" = "/admin/structure/colors/add",
 *     "edit-form" = "/admin/structure/colors/{color}/edit",
 *     "delete-form" = "/admin/structure/colors/{color}/delete",
 *     "collection" = "/admin/structure/colors"
 *   }
 * )
 */
class Color extends ContentEntityBase implements ColorInterface {

  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields['id'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('ID'))
      ->setDescription(t('The ID of the Color entity.'))
      ->setReadOnly(TRUE)
      ->setSetting('unsigned', TRUE)
      ->setCardinality(1);

    $fields['uuid'] = BaseFieldDefinition::create('uuid')
      ->setLabel(t('UUID'))
      ->setDescription(t('The UUID of the Color entity.'))
      ->setReadOnly(TRUE)
      ->setCardinality(1);

    $fields['name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Name'))
      ->setDescription(t('The name of the color.'))
      ->setRequired(TRUE)
      ->setSetting('max_length', 255);

    $fields['hex_value'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Hex Value'))
      ->setDescription(t('The hex value of the color (e.g., #FF0000).'))
      ->setRequired(TRUE)
      ->setSetting('max_length', 7);

    $fields['rgb_value'] = BaseFieldDefinition::create('string')
      ->setLabel(t('RGB Value'))
      ->setDescription(t('The RGB value of the color (e.g., rgb(255, 0, 0)).'))
      ->setSetting('max_length', 255);

    $fields['tags'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Tags'))
      ->setDescription(t('Comma-separated list of tags.'))
      ->setSetting('max_length', 255);

    return $fields;
  }
}

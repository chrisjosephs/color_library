<?php

namespace Drupal\color_library\Entity;
use Drupal\Core\Entity\Attribute\ContentEntityType;
use Drupal\color_library\Entity\ColorInterface;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\StringTranslation\TranslatableMarkup;

/**
 * Defines the Color entity.
 *
 */

#[ContentEntityType(
  id: "color",
  label: new TranslatableMarkup('Color'),
  label_singular: new TranslatableMarkup('color item'),
  label_plural: new TranslatableMarkup('color items'),
  entity_keys: [
    'id' => 'cid',
    'label' => 'name',
    'uuid' => 'uuid',
    'published' => 'status',
    'owner' => 'uid',
  ],
  links: [
    "canonical" => "/admin/structure/colors/{color}",
    "add-form" => "/admin/structure/colors/add",
    "edit-form" => "/admin/structure/colors/{color}/edit",
    "delete-form" => "/admin/structure/colors/{color}/delete",
    "collection" => "/admin/structure/colors"
   ],
  admin_permission: "administer color entities",
  base_table: 'color',
  field_ui_base_route: "entity.color.admin_form",
  )]

class Color extends ContentEntityBase implements ColorInterface {

  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields['cid'] = BaseFieldDefinition::create('integer')
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

    // $fields['css_variable_name'] = BaseFieldDefinition::create('string')
    //  ->setLabel(t('Css Variable'))
    //  ->setDescription(t('The css variable name of the color.'))
    //  ->setRequired(TRUE)
    //  ->setSetting('max_length', 255);

    return $fields;
  }

  public function getHexValue()
  {
    // TODO: Implement getHexValue() method.
  }

  public function getRgbValue()
  {
    // TODO: Implement getRgbValue() method.
  }

  public function getDescription()
  {
    // TODO: Implement getDescription() method.
  }

  public function setHexValue($hex_value)
  {
    // TODO: Implement setHexValue() method.
  }
}

<?php

namespace Drupal\custom_color_field\Plugin\Field\FieldType;

use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\Field\Plugin\Field\FieldType\EntityReferenceItem;
use Drupal\Core\TypedData\DataDefinition;

/**
 * Defines the 'color_reference#' field type.
 *
 * @FieldType(
 *   id = "color_reference",
 *   label = @Translation("Color Reference with Metadata"),
 *   description = @Translation("An entity reference field for Color with additional fields."),
 *   category = @Translation("Reference"),
 *   default_widget = "color_reference_with_metadata_widget",
 *   default_formatter = "color_reference_with_metadata_formatter"
 * )
 */
class ColorReferenceItem extends EntityReferenceItem {

  /**
   * {@inheritdoc}
   */
  public static function propertyDefinitions(FieldStorageDefinitionInterface $field_definition) {
    // Get the parent entity reference field properties.
    $properties = parent::propertyDefinitions($field_definition);

    // Add a property to reference Color entity by its 'target_id'.
    $properties['target_id'] = DataDefinition::create('integer')
      ->setLabel(t('Referenced Color ID'))
      ->setDescription(t('The ID of the referenced color entity.'));

    // Add an extra field for metadata, e.g., "context".
    $properties['context'] = DataDefinition::create('string')
      ->setLabel(t('Context'))
      ->setDescription(t('Additional metadata about the color reference.'));

    return $properties;
  }

  /**
   * {@inheritdoc}
   */
  public static function schema(FieldStorageDefinitionInterface $field_definition) {
    // Get the parent entity reference schema.
    $schema = parent::schema($field_definition);
    // Add the extra 'opacity' column.
    $schema['columns']['opacity'] = [
      'type' => 'int',
      'size' => 'tiny',
      'not null' => FALSE,
      'default' => 100,
      'description' => 'The opacity of the color.',
    ];
    // Add the extra 'context' column.
    $schema['columns']['context'] = [
      'type' => 'varchar',
      'length' => 255,
    ];

    return $schema;
  }
}

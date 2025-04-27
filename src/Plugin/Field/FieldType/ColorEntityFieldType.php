<?php

namespace Drupal\color_library\Plugin\Field\FieldType;

use Drupal\Core\Field\FieldItemBase;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\TypedData\DataDefinition;

/**
 * Defines the 'color entity' field type.
 *
 * @FieldType(
 *   id = "color_entity_field",
 *   label = @Translation("Color Entity Field"),
 *   description = @Translation("A field to reference a color entity."),
 *   default_widget = "entity_reference_autocomplete",
 *   default_formatter = "entity_reference_label"
 * )
 */
class ColorEntityFieldType extends FieldItemBase {

  /**
   * {@inheritdoc}
   */
  public static function propertyDefinitions(FieldStorageDefinitionInterface $field_definition) {
    $properties = [];

    // Add a property to reference Color entity by its 'target_id'.
    $properties['target_id'] = DataDefinition::create('integer')
      ->setLabel(t('Referenced Color ID'))
      ->setDescription(t('The ID of the referenced color entity.'));

    return $properties;
  }

  /**
   * {@inheritdoc}
   */
  public static function schema(FieldStorageDefinitionInterface $field_definition) {
    return [
      'columns' => [
        // Reference target ID.
        'target_id' => [
          'type' => 'int',
          'not null' => FALSE,
        ],
      ],
      'indexes' => [
        // Add an index for efficient lookups based on the referenced entity.
        'target_id' => ['target_id'],
      ],
      'foreign keys' => [
        // Create a foreign key to the 'color' entity table.
        'target_id' => [
          'table' => 'color', // Name of the referenced entity's table.
          'columns' => ['target_id' => 'cid'], // Maps this field's target_id to the Color entity's 'cid'.
        ],
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function isEmpty() {
    $target_id = $this->get('target_id')->getValue();
    return empty($target_id);
  }
}

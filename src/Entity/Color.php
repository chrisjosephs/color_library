<?php

namespace Drupal\color_library\Entity;

use Drupal\color_library\ColorAccessControlHandler;
use Drupal\color_library\ColorViewsData;
use Drupal\Core\Entity\Attribute\ContentEntityType;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\ContentEntityDeleteForm;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Entity\EntityViewBuilder;
use Drupal\Core\Entity\Form\DeleteMultipleForm;
use Drupal\Core\Entity\Form\RevisionDeleteForm;
use Drupal\Core\Entity\Form\RevisionRevertForm;
use Drupal\Core\Entity\Routing\RevisionHtmlRouteProvider;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\StringTranslation\TranslatableMarkup;


/**
 * Defines the Color entity.
 */
#[ContentEntityType(
  id: 'color',
  label: new TranslatableMarkup('Color'),
  label_singular: new TranslatableMarkup('color'),
  label_plural: new TranslatableMarkup('colors'),
  entity_keys: [
    'id' => 'cid',
    'label' => 'name',
    'uuid' => 'uuid',
    'revision' => 'vid',

  ],
  handlers: [
    'view_builder' => EntityViewBuilder::class,
    //'list_builder' => MediaListBuilder::class,
    'access' => ColorAccessControlHandler::class,
    'form' => [
   //   'default' => MediaForm::class,
   //   'add' => MediaForm::class,
    //  'edit' => MediaForm::class,
      'delete' => ContentEntityDeleteForm::class,
  ///    'delete-multiple-confirm' => DeleteMultipleForm::class,
   //   'revision-delete' => RevisionDeleteForm::class,
    //  'revision-revert' => RevisionRevertForm::class,
    ],
    'views_data' => ColorViewsData::class,
    // 'route_provider' => [
    //  'html' => MediaRouteProvider::class,
    //  'revision' => RevisionHtmlRouteProvider::class,
    //],
  ], // Table to store revisions
  links: [
    "canonical" => "/admin/structure/colors/{color}",
    "add-form" => "/admin/structure/colors/add",
    "edit-form" => "/admin/structure/colors/{color}/edit",
    "delete-form" => "/admin/structure/colors/{color}/delete",
    "collection" => "/admin/structure/colors",
  ],
  base_table: 'color',
  revision_table: 'color_revision',
  revision_data_table: 'color_field_revision',
  translatable: TRUE,
  show_revision_ui: TRUE,
  // admin_permission: "administer colors",
  label_count: [
    'singular' => '@count color',
    'plural' => '@count colors',
  ],
  field_ui_base_route: "entity.color.admin_form",
  revision_metadata_keys: [
    'revision_user' => 'revision_user',
    'revision_created' => 'revision_created',
    'revision_log_message' => 'revision_log_message',
  ],
  )]

class Color extends ContentEntityBase implements ColorInterface {
  /**
   *
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields['cid'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Color ID'))
      ->setDescription(t('The unique ID of the color entity.'))
      ->setRequired(TRUE)
      ->setSetting('max_length', 255)
      ->setSetting('is_ascii', TRUE);

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

    $fields['hexadecimal'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Hex Value'))
      ->setDescription(t('The hex value of the color (e.g., #FF0000).'))
      ->setRequired(TRUE)
      ->setSetting('max_length', 7);

    $fields['tags'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Tags'))
      ->setDescription(t('Comma-separated list of tags.'))
      ->setSetting('max_length', 255);

    $fields['css_variable_name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Css Variable'))
      ->setDescription(t("The css variable name of the color to match the setting in your themes' css files."))
      ->setRequired(FALSE)
      ->setSetting('max_length', 255);

    return $fields;
  }

  /**
   *
   */
  public function getHexValue() {
    // @todo Implement getHexValue() method.
  }

  /**
   *
   */
  public function getRgbValue() {
    // @todo Implement getRgbValue() method.
  }

  /**
   *
   */
  public function getDescription() {
    // @todo Implement getDescription() method.
  }

  /**
   *
   */
  public function setHexValue($hexadecimal) {
    // @todo Implement setHexValue() method.
    }

    /**
     *
     */
    public function setName($name) {
      // @todo Implement seName() method.
    }

  /**
   * Check where this color is used to warn user if updated
   */
  public function whatUsesColor($id, ColorInterface $color) {
    $additionalText = "This color is used in the following places: @count, [@ places]";
    $additionalText2 = "Note: this won't update where you have used the colour in the WYSIWYG editor or in CSS files in the past, only entities that link to this color entity";
  }
}

<?php

namespace Drupal\color_library\Plugin\GraphQL\Mutations;

use Drupal\graphql\Plugin\GraphQL\Mutations\MutationPluginBase;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Create a new Color entity.
 *
 * @GraphQLMutation(
 *   id = "create_color",
 *   secure = true,
 *   name = "createColor",
 *   type = "Color",
 *   arguments = {
 *     "cid" = "Color ID/Machine name"
 *     "name" = "String",
 *     "hexadecimal" = "String",
 *     "css_variable_name" = "String"
 *   },
 *   description = @Translation("Create a new Color entity.")
 * )
 */
class CreateColor extends MutationPluginBase {

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Constructs a CreateColor mutation plugin.
   *
   * @param array $configuration
   * @param string $plugin_id
   * @param mixed $plugin_definition
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   */
  public function __construct(
    array $configuration,
    $plugin_id,
    $plugin_definition,
    EntityTypeManagerInterface $entity_type_manager
  ) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * {@inheritdoc}
   */
  public function resolve(array $args) {
    $color = $this->entityTypeManager->getStorage('color')->create([
      'cid' => $args['cid'],
      'name' => $args['name'],
      'hexadecimal' => $args['hexadecimal'],
      'css_variable_name' => $args['css_variable_name'],
    ]);
    $color->save();
    return $color;
  }

}

<?php

namespace Drupal\color_library\Plugin\Block;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Access\AccessResult;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\Session\AccountInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a color palette floating icon and modal interface block that appears on entity edit pages in admin/edit pages to easily access colors and copy/paste them into whatever is being edited.
 *
 * @Block(
 *   id = "color_palette_accessory",
 *   admin_label = @Translation("Color Palette Floating Accessory"),
 * )
 */
class ColorPaletteAccessoryBlock extends BlockBase implements ContainerFactoryPluginInterface {

  protected EntityTypeManagerInterface $entityTypeManager;
  protected RouteMatchInterface $routeMatch;

  public function __construct(array $configuration, $plugin_id, $plugin_definition, EntityTypeManagerInterface $entity_type_manager, RouteMatchInterface $route_match) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->entityTypeManager = $entity_type_manager;
    $this->routeMatch = $route_match;
  }

  /**
   *
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('entity_type.manager'),
      $container->get('current_route_match')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    // You can add any content you want to display in the block here.
    // For example, you could get the current entity being edited:
    $entity = $this->getCurrentEntity();

    $build = [];
    if ($entity) {
      $build['#markup'] = '<p>' . $this->t('You are editing a @entity_type entity.', ['@entity_type' => $entity->getEntityType()->getLabel()]) . '</p>';
      // Add more content based on the entity type or other criteria.
      if ($entity->getEntityTypeId() == 'node') {
        $build['#markup'] .= '<p>This is a node</p>';
      }

      if ($entity->getEntityTypeId() == 'paragraph') {
        $build['#markup'] .= '<p>This is a paragraph</p>';
      }

    }
    else {
      $build['#markup'] = '<p>' . $this->t('No entity is being edited.') . '</p>';
    }

    return $build;
  }

  /**
   * {@inheritdoc}
   */
  protected function blockAccess(AccountInterface $account) {
    // Only show the block on admin pages and entity edit pages.
    /**
     * @todo this should just be managed from block configuration page really?
     */
    $route_name = $this->routeMatch->getRouteName();

    if ($route_name && str_starts_with($route_name, 'entity.') && str_contains($route_name, '.edit_form') && !\Drupal::service('path.matcher')
        ->isFrontPage()) {
      return AccessResult::allowedIf($account->hasPermission('access administration pages'));
    }

    return AccessResult::forbidden();
  }

  /**
   * Helper function to get current entity.
   *
   * @return \Drupal\Core\Entity\ContentEntityInterface|null
   */
  protected function getCurrentEntity() {

    $entity = NULL;
    $route_match = \Drupal::routeMatch();
    $parameters = $route_match->getParameters();

    foreach ($parameters as $parameter) {
      if ($parameter instanceof ContentEntityInterface) {
        $entity = $parameter;
        break;
      }
    }

    return $entity;
  }

}

<?php

namespace Drupal\color_library\Plugin\Block;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Routing\RouteMatchInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a color palette block that appears on entity edit pages in admin/edit pages to easily access colors and copy/paste them into whatever is being edited
 *
 * @Block(
 *   id = "entity_edit_admin_block",
 *   admin_label = @Translation("Entity Edit Admin Block"),
 * )
 */
class ColorPaletteBlock extends BlockBase implements ContainerFactoryPluginInterface {

  protected $entityTypeManager;
  protected $routeMatch;

  public function __construct(array $configuration, $plugin_id, $plugin_definition, EntityTypeManagerInterface $entity_type_manager, RouteMatchInterface $route_match) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->entityTypeManager = $entity_type_manager;
    $this->routeMatch = $route_match;
  }

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
        if($entity->getEntityTypeId() == 'node'){
            $build['#markup'] .= '<p>This is a node</p>';
        }

        if($entity->getEntityTypeId() == 'paragraph'){
            $build['#markup'] .= '<p>This is a paragraph</p>';
        }

    } else {
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
     * @todo: this should just be managed from block configuration page really?
     */
    $route_name = $this->routeMatch->getRouteName();

    if ($route_name && strpos($route_name, 'entity.') === 0 && strpos($route_name, '.edit_form') !== false && \Drupal::service('path.matcher')->isFrontPage() == FALSE ) {
        return AccessResult::allowedIf($account->hasPermission('access administration pages'));
    }

    return AccessResult::forbidden();
  }


    /**
     * Helper function to get current entity
     *
     * @return \Drupal\Core\Entity\ContentEntityInterface|null
     */
    protected function getCurrentEntity() {

        $entity = NULL;
        $route_match = \Drupal::routeMatch();
        $parameters = $route_match->getParameters();

        foreach ($parameters as $parameter) {
            if ($parameter instanceof \Drupal\Core\Entity\ContentEntityInterface) {
                $entity = $parameter;
                break;
            }
        }

        return $entity;
    }


}

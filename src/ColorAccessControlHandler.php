<?php

namespace Drupal\color_library;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Color entity.
 */
class ColorAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    // Check permissions for operations (view, edit, delete).
    switch ($operation) {
      case 'view':
        // if (!$entity->isPublished()) { - always is published
          // For unpublished, allow access only to users with specific permission.
          return AccessResult::allowedIfHasPermission($account, 'view unpublished colors');
        // }
        return AccessResult::allowedIfHasPermission($account, 'view published colors');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit colors');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete colors');
    }

    // Return neutral if no operation matches.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}

  protected function checkCreateAccess(AccountInterface $account, array $context, EntityTypeInterface $entity_bundle = null) {
    // Check if the user has permission to create new colors.
    return AccessResult::allowedIfHasPermission($account, 'add colors');
  }
   */
}

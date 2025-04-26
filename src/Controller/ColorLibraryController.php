<?php

namespace Drupal\color_library\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\views\ViewExecutableFactory;

class ColorLibraryController extends ControllerBase {

  /**
   * Display the color library.
   */
  public function view(Request $request) {
    $view = \Drupal::entityTypeManager()->getStorage('view')->load('color_library');
    $view->setDisplay('color_library');
    $build = \Drupal::entityTypeManager()->getViewBuilder('view')->view($view);

    return $build;
  }

}

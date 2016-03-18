<?php

/**
 * @file
 * Contains Drupal\social_api\Controller\SocialApiController
 */

namespace Drupal\social_api\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Menu\LocalTaskManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

class SocialApiController extends ControllerBase
{
  /**
   * @var LocalTaskManager
   */
  private $localTaskManager;

  /**
   * @inheritdoc
   */
  public static function create(ContainerInterface $container)
  {
    $localTaskManager = $container->get('plugin.manager.menu.local_task');

    return new static($localTaskManager);
  }

  /**
   * SocialApiController constructor.
   * @param LocalTaskManager $localTaskManager
   */
  public function __construct(LocalTaskManager $localTaskManager)
  {
    $this->localTaskManager = $localTaskManager;
  }

  /**
   * Render the list of plugins for a social network
   *
   * @param string $route
   * @return array
   */
  public function pluginsPage($route)
  {
    $build = [
      '#theme' => 'plugins_list',
    ];

    $tasksList = $this->localTaskManager->getDefinitions();

    $plugins = array();

    foreach($tasksList as $taskName => $task) {
      if($task['base_route'] == $route) {
        $plugins[$taskName]['route_name'] = $task['route_name'];
        $plugins[$taskName]['title'] = $task['title']->render();
      }
    }

    $build['#plugins'] = $plugins;

    return $build;
  }
  
  public function autosharing()
  {
    $build = [
      '#markup' => 'All the enabled, autosharing-related sub-modules should be listed here',
    ];
    
    return $build;
  }
  
}

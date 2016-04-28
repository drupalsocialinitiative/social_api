<?php

/**
 * @file
 * Contains Drupal\social_api\Controller\SocialApiController
 */

namespace Drupal\social_api\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Menu\LocalTaskManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\social_api\Plugin\NetworkManager;

class SocialApiController extends ControllerBase
{
  /**
   * @var NetworkManager
   */
  private $networkManager;

  /**
   * @inheritdoc
   */
  public static function create(ContainerInterface $container) {
    return new static($container->get('plugin.network.manager'));
  }


  /**
   * SocialApiController constructor.
   * @param NetworkManager $networkManager
   */
  public function __construct(NetworkManager $networkManager) {
    $this->networkManager = $networkManager;
  }

  /**
   * Render the list of plugins for a social network
   *
   * @param $type
   * @return array
   */
  public function integrations($type) {
    $networks = $this->networkManager->getDefinitions();
    $header = [
      $this->t('Machine name'),
      $this->t('Label'),
    ];
    $data = [];
    foreach ($networks as $network) {
      if($network['type'] == $type) {
        $data[] = [
          $network['id'],
          $network['label'],
        ];
      }
    }
    return [
      '#theme' => 'table',
      '#header' => $header,
      '#rows' => $data,
      '#empty' => $this->t('There are no social integrations enabled.'),
    ];
  }
}

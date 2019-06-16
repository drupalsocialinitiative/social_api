<?php

use Drupal\social_api\Controller\SocialApiController;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\social_api\Plugin\NetworkManager;
use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Tests\UnitTestCase;

/**
 * Defines Controller.
 *
 * @Annotation
 */
class ControllerTest extends UnitTestCase {

  /**
   * Tests for class SocialApiController.
   */
  public function testSocialApiController() {
    $namespaces = $this->createMock(Traversable::class);
    $cache_backend = $this->createMock(CacheBackendInterface::class);
    $module_handler = $this->createMock(ModuleHandlerInterface::class);
    $container = $this->createMock(ContainerInterface::class);

    $networkManager = $this->getMockBuilder(NetworkManager::class)
      ->setConstructorArgs([$namespaces, $cache_backend, $module_handler])
      ->getMock();

    $controller = $this->getMockBuilder(SocialApiController::class)
      ->setConstructorArgs([$networkManager])
      ->getMock();

    $this->assertTrue(
      method_exists($controller, 'create'),
      'SocialApiController class does not implements create function/method'
    );
    $this->assertTrue(
      method_exists($controller, 'integrations'),
      'SocialApiController class does not implements integrations function/method'
    );
  }

}

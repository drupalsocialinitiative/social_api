<?php

use Drupal\social_api\AuthManager\OAuth2Manager;
use Drupal\social_api\AuthManager\OAuth2ManagerInterface;
use Drupal\Tests\UnitTestCase;

/**
 * Defines OAuth2Manager.
 *
 * @Annotation
 */
class AuthManagerTest extends UnitTestCase {

  /**
   * Tests for class Network.
   */
  public function testOAuth2Manager() {
    $authManager = $this->getMockBuilder(OAuth2Manager::class)
      ->getMockForAbstractClass();

    $this->assertTrue(
      method_exists($authManager, 'setClient'),
        'OAuth2Manager class does not implements setClient function/method'
      );

    $this->assertTrue(
      method_exists($authManager, 'getClient'),
        'OAuth2Manager class does not implements getClient function/method'
      );

    $this->assertTrue(
      method_exists($authManager, 'setAccessToken'),
        'OAuth2Manager class does not implements setAccessToken function/method'
      );

    $this->assertTrue(
      method_exists($authManager, 'getAccessToken'),
        'OAuth2Manager class does not implements getAccessToken function/method'
      );

    $authManager->setClient('drupal12345');

    $authManager->setAccessToken('drupal12345');

    $this->assertEquals('drupal12345', $authManager->getClient());
    $this->assertEquals('drupal12345', $authManager->getAccessToken());
  }

  /**
   * Tests for class Network.
   */
  public function testOAuth2ManagerInterface() {
    $authManagerInterface = $this->createMock(OAuth2ManagerInterface::class);

    $this->assertTrue(
      method_exists($authManagerInterface, 'setClient'),
        'OAuth2ManagerInterface does not have setClient function/method'
      );

    $this->assertTrue(
      method_exists($authManagerInterface, 'getClient'),
        'OAuth2ManagerInterface does not have getClient function/method'
      );

    $this->assertTrue(
      method_exists($authManagerInterface, 'getAccessToken'),
        'OAuth2ManagerInterface does not have getAccessToken function/method'
      );

    $this->assertTrue(
      method_exists($authManagerInterface, 'setAccessToken'),
        'OAuth2ManagerInterface does not have setAccessToken function/method'
      );

    $this->assertTrue(
    method_exists($authManagerInterface, 'getAuthorizationUrl'),
        'OAuth2ManagerInterface does not have getAuthorizationUrl function/method'
      );

    $this->assertTrue(
      method_exists($authManagerInterface, 'getState'),
        'OAuth2ManagerInterface does not have getState function/method'
      );

    $this->assertTrue(
      method_exists($authManagerInterface, 'getUserInfo'),
        'OAuth2ManagerInterface does not have getUserInfo function/method'
      );
  }

}

<?php

namespace Drupal\Tests\social_api\Unit;

use Drupal\social_api\SocialApiDataHandler;
use Drupal\Tests\UnitTestCase;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Tests for the SocialApiDataHandler.
 *
 * @coversDefaultClass \Drupal\social_api\SocialApiDataHandler
 * @group SocialAPI
 */
class DataHandlerTest extends UnitTestCase {

  /**
   * Tests for class SocialApiDataHandler.
   */
  public function testSocialApiDataHandler() {
    $key = 'drupal';
    $value = 'drupal123';
    $preffix = 'social_api_test';

    $session = $this->getMockBuilder(Session::class)
      ->setMethods(['set', 'get'])
      ->getMock();

    // Mock the get method of the session service.
    $session->method('get')
      ->with($preffix . '_' . $key)
      ->willReturn($value);

    /** @var \Drupal\social_api\SocialApiDataHandler|\PHPUnit_Framework_MockObject_MockObject $dataHandler */
    $dataHandler = $this->getMockBuilder(SocialApiDataHandler::class)
      ->setConstructorArgs([$session])
      ->setMethods(NULL)
      ->getMock();

    $dataHandler->setSessionPrefix($preffix);
    $dataHandler->set($key, $value);

    $this->assertEquals($preffix . '_', $dataHandler->getSessionPrefix());
    $this->assertEquals($value, $dataHandler->get($key));
  }

}

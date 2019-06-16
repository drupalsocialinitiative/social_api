<?php

use Drupal\social_api\SocialApiDataHandler;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Drupal\Tests\UnitTestCase;

/**
 * Defines SocialApiDataHandler class.
 *
 * @Annotation
 */
class SocialApiDataHandlerTest extends UnitTestCase {

  /**
   * Tests for class SocialApiDataHandler.
   */
  public function testSocialApiDataHandler() {
    $key = "drupal";
    $value = "drupal123";
    $session = $this->getMock(SessionInterface::class);

    $socialApiDataHandler = $this->getMockBuilder(SocialApiDataHandler::class)
      ->setConstructorArgs([$session])
      ->setMethods(['set', 'get'])
      ->getMockForAbstractClass();

    $socialApiDataHandler->method('get')
      ->with($key)
      ->willReturn($session->get($socialApiDataHandler->getSessionPrefix() . $key));

    $socialApiDataHandler->setSessionPrefix('1234');

    $this->assertEquals('1234_', $socialApiDataHandler->getSessionPrefix());
    $this->assertEquals($session->get($socialApiDataHandler->getSessionPrefix() . $key), $socialApiDataHandler->get($key));
  }

}

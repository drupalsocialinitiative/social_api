<?php

namespace Drupal\Tests\social_api\Unit;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\Core\Session\AccountProxyInterface;
use Drupal\social_api\SocialApiDataHandler;
use Drupal\social_api\User\UserAuthenticator;
use Drupal\social_api\User\UserManager;
use Drupal\Tests\UnitTestCase;

/**
 * Tests social_api User.
 *
 * @group social_api
 */
class SocialApiUserTest extends UnitTestCase {

  /**
   * The tested Social Api UserManager.
   *
   * @var \Drupal\social_api\User\UserManager
   */
  protected $userManager;

  /**
   * The tested Social Api UserAuthenticator.
   *
   * @var \Drupal\social_api\User\UserAuthenticator|\PHPUnit_Framework_MockObject_MockObject\PHPUnit_Framework_MockObject_MockObject
   */
  protected $userAuthenticator;

  /**
   * The mocked Social Api Data Handler.
   *
   * @var \Drupal\social_api\SocialApiDataHandler|\PHPUnit_Framework_MockObject_MockObject
   */
  protected $dataHandler;

  /**
   * The mocked array of the session keys.
   *
   * @var array
   */
  protected $sessionKeys;

  /**
   * {@inheritdoc}
   */
  public function setUp() {
    $current_user = $this->createMock(AccountProxyInterface::class);
    $entity_type_manager = $this->createMock(EntityTypeManagerInterface::class);
    $logger_factory = $this->createMock(LoggerChannelFactoryInterface::class);
    $messenger = $this->createMock(MessengerInterface::class);

    $this->dataHandler = $this->getMockBuilder(SocialApiDataHandler::class)
      ->disableOriginalConstructor()
      ->setMethods(['get', 'set', 'getSessionPrefix'])
      ->getMock();

    $entity_type = 'users';

    $this->sessionKeys = [];

    $this->userManager = $this->getMockBuilder(UserManager::class)
      ->setConstructorArgs([$entity_type,
        $entity_type_manager,
        $messenger,
        $logger_factory,
      ])
      ->setMethods(NULL)
      ->getMock();

    $this->userAuthenticator = $this->getMockBuilder(UserAuthenticator::class)
      ->setConstructorArgs([$current_user,
        $messenger,
        $logger_factory,
        $this->userManager,
        $this->dataHandler,
      ])
      ->setMethods(NULL)
      ->getMock();

  }

  /**
   * @covers Drupal\social_api\User\UserManager::setPluginId
   */
  public function testSetPluginId() {
    $this->assertEquals(NULL, $this->userManager->getPluginId());
    $this->userManager->setPluginId('social_auth_test');
    $this->assertEquals('social_auth_test', $this->userManager->getPluginId());
  }

  /**
   * @covers Drupal\social_api\User\UserManager::getPluginId
   */
  public function testGetPluginId() {
    $this->userManager->setPluginId('social_auth_test2');
    $this->assertEquals('social_auth_test2', $this->userManager->getPluginId());
  }

  /**
   * @covers Drupal\social_api\User\UserAuthenticator::setPluginId
   */
  public function testSetPluginIdAuthenticator() {
    $this->assertEquals(NULL, $this->userAuthenticator->getPluginId());
    $this->userAuthenticator->setPluginId('social_auth_test');
    $this->assertEquals('social_auth_test', $this->userAuthenticator->getPluginId());
  }

  /**
   * @covers Drupal\social_api\User\UserAuthenticator::getPluginId
   */
  public function testGetPluginIdAuthenticator() {
    $this->userAuthenticator->setPluginId('social_auth_test2');
    $this->assertEquals('social_auth_test2', $this->userAuthenticator->getPluginId());
  }

  /**
   * @covers Drupal\social_api\User\UserAuthenticator::getSessionKeys
   */
  public function testGetSessionKeys() {
    $sample_session = ['h78323' => '78t2gq2g7q', 'pawdwadawd' => 'cbzhzxc'];

    $this->userAuthenticator->setSessionKeysToNullify(array_keys($sample_session));
    $this->assertEquals(array_keys($sample_session), $this->userAuthenticator->getSessionKeys());
  }

  /**
   * @covers Drupal\social_api\User\UserAuthenticator::setSessionKeysToNullify
   */
  public function testSetSessionKeysToNullify() {
    $sample_session = ['h78323' => '78t2gq2g7q', 'pawdwadawd' => 'cbzhzxc'];

    $this->assertNotEquals(array_keys($sample_session), $this->userAuthenticator->getSessionKeys());
    $this->userAuthenticator->setSessionKeysToNullify(array_keys($sample_session));
    $this->assertEquals(array_keys($sample_session), $this->userAuthenticator->getSessionKeys());
  }

  /**
   * @covers Drupal\social_api\User\UserAuthenticator::nullifySessionKeys
   */
  public function testNullifySessionKeys() {
    $sample_session = ['h78323' => '78t2gq2g7q'];

    $this->dataHandler->expects($this->any())
      ->method('getSessionPrefix')
      ->will($this->returnCallback(function () {
        return 'xSn2ax_';
      }));

    $this->dataHandler->expects($this->any())
      ->method('get')
      ->with($this->isType('string'))
      ->will($this->returnCallback(function ($key) {
        return $this->sessionKeys[$this->dataHandler->getSessionPrefix() . $key];
      }));

    $this->dataHandler->expects($this->any())
      ->method('set')
      ->with($this->isType('string'), $this->anything())
      ->will($this->returnCallback(function ($key, $value) {
        $this->sessionKeys[$this->dataHandler->getSessionPrefix() . $key] = $value;
      }));

    $this->dataHandler->set('h78323', '78t2gq2g7q');
    $this->assertEquals('78t2gq2g7q', $this->dataHandler->get('h78323'));

    $this->userAuthenticator->setSessionKeysToNullify(array_keys($sample_session));
    $this->assertEquals(array_keys($sample_session), $this->userAuthenticator->getSessionKeys());

    $this->userAuthenticator->nullifySessionKeys();

    $this->assertEquals(NULL, $this->dataHandler->get('h78323'));
  }

}

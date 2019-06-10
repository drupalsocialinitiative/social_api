<?php

use Drupal\social_api\User\UserAuthenticator;
use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\Core\Session\AccountProxyInterface;
use Drupal\social_api\SocialApiDataHandler;
use Drupal\social_api\User\UserManagerInterface;
use Drupal\social_api\User\UserManager;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Tests\UnitTestCase;

/**
 * Defines a User class.
 *
 * @Annotation
 */
class UserTest extends UnitTestCase {

  protected $entityType;

  /**
   * Define __construct function.
   */
  public function __construct() {
    parent::__construct();
  }

  /**
   * {@inheritdoc}
   */
  public function setUp() {
    parent::setUp();
  }

  /**
   * Tests for class UserAuthenticator.
   */
  public function testUserAuthenticator() {
    $sessionKeys = array('drupal', 'drupal234', '1234');
    $current_user = $this->createMock(AccountProxyInterface::class);
    $messenger = $this->createMock(MessengerInterface::class);
    $logger_factory = $this->createMock(LoggerChannelFactoryInterface::class);
    $user_manager = $this->createMock(UserManagerInterface::class);
    $data_handler = $this->createMock(SocialApiDataHandler::class);

    $userAuthenticator = $this->getMockBuilder(UserAuthenticator::class)
      ->setConstructorArgs(array($current_user,
        $messenger,
        $logger_factory,
        $user_manager,
        $data_handler,
      ))
      ->getMockForAbstractClass();
    $this->assertTrue(
      method_exists($userAuthenticator, 'setPluginId'),
      'UserAuthenticator does not implements setPluginId function/method'
    );
    $this->assertTrue(
      method_exists($userAuthenticator, 'getPluginId'),
      'UserAuthenticator does not implements getPluginId function/method'
    );
    $this->assertTrue(
      method_exists($userAuthenticator, 'setSessionKeysToNullify'),
      'UserAuthenticator does not implements setSessionKeysToNullify function/method'
    );
    $this->assertTrue(
      method_exists($userAuthenticator, 'nullifySessionKeys'),
      'UserAuthenticator does not implements nullifySessionKeys function/method'
    );
    $userAuthenticator->setPluginId('drupal123');
    $dataHandler = $data_handler;
    $session_key = array();
    $dataHandler->setSessionPrefix('1234');
    $userAuthenticator->setSessionKeysToNullify($session_key);
    $this->assertEquals('drupal123', $userAuthenticator->getPluginId());
    $this->assertEquals(NULL, $userAuthenticator->nullifySessionKeys());
  }

  /**
   * Tests for class UserManager.
   */
  public function testUserManager() {
    $entity_type_manager = $this->createMock(EntityTypeManagerInterface::class);
    $messenger = $this->createMock(MessengerInterface::class);
    $logger_factory = $this->createMock(LoggerChannelFactoryInterface::class);
    $userManager = $this->getMockBuilder(UserManager::class)
      ->setConstructorArgs(array($this->entityType,
        $entity_type_manager,
        $messenger,
        $logger_factory,
      ))
      ->getMockForAbstractClass();
    $this->assertTrue(
      method_exists($userManager, 'setPluginId'),
      'UserManager does not implements setPluginId function/method'
    );
    $this->assertTrue(
      method_exists($userManager, 'getPluginId'),
      'UserManager does not implements getPluginId function/method'
    );
    $this->assertTrue(
      method_exists($userManager, 'getDrupalUserId'),
      'UserManager does not implements getDrupalUserId function/method'
    );
    $this->assertTrue($userManager instanceof UserManager);
    $userManager->setPluginId('drupal123');
    $this->assertEquals('drupal123', $userManager->getPluginId());
  }

  /**
   * Tests for class UserManagerInterface.
   */
  public function testUserManagerInterface() {
    $userManagerInterface = $this->createMock(UserManagerInterface::class);
    $this->assertTrue($userManagerInterface instanceof UserManagerInterface);
    $this->assertTrue(
      method_exists($userManagerInterface, 'getPluginId'),
      'UserManagerInterface does not implements getPluginId function/method'
    );
    $this->assertTrue(
      method_exists($userManagerInterface, 'setPluginId'),
      'UserManagerInterface does not implements fsetPluginId function/method'
    );
    $this->assertTrue(
      method_exists($userManagerInterface, 'getDrupalUserId'),
      'UserManagerInterface does not implements getDrupalUserId function/method'
    );
  }

}

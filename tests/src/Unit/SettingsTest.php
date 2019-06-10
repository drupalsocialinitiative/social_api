<?php

use Drupal\social_api\Settings\SettingsBase;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Drupal\Core\Config\StorageInterface;
use Drupal\Core\Config\TypedConfigManagerInterface;
use Drupal\Tests\UnitTestCase;

/**
 * Defines Settings Class.
 *
 * @Annotation
 */
class SettingsTest extends UnitTestCase {

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
   * Tests for class Settings.
   */
  public function testSettingsBase() {
    $config = $this->getMockBuilder('Drupal\Core\Config\Config')
      ->disableOriginalConstructor()
      ->getMock();

    $storage = $this->createMock(StorageInterface::class);
    $event_dispatcher = $this->createMock(EventDispatcherInterface::class);
    $typed_config = $this->createMock(TypedConfigManagerInterface::class);
    $configs = $this->getMockBuilder('Drupal\Core\Config\ImmutableConfig')
      ->setConstructorArgs(array($config,
        $storage,
        $event_dispatcher,
        $typed_config,
      ))
      ->getMock();

    $settingsBase = $this->getMockBuilder(SettingsBase::class)
      ->setConstructorArgs(array($configs))
      ->getMockForAbstractClass();

    $this->assertTrue(
      method_exists($settingsBase, 'getConfig'),
      'SettingsBase does not implements getConfig function/method'
    );
    $this->assertTrue(
      method_exists($settingsBase, 'factory'),
      'SettingsBase does not implements factory function/method'
    );
    $settingsBase->getConfig();
  }

}

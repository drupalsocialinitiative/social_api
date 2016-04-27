<?php

/**
 * @file
 * Contains \Drupal\social_autopost\Plugin\NetworkBase.
 */

namespace Drupal\social_api\Plugin;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Plugin\PluginBase;
use Drupal\social_autopost\Settings\SettingsInterface;
use Drupal\social_api\SocialApiException;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Base class for Social Network plugins.
 */
abstract class NetworkBase extends PluginBase implements NetworkInterface {

  /**
   * Stores the settings wrapper object.
   *
   * @var SettingsInterface
   */
  protected $settings;

  /**
   * The 3rd party SDK library that will be used to do the publication.
   *
   * Every network will have a different object class.
   *
   * @var mixed
   */
  protected $sdk;

  /**
   * The entity type manager.
   *
   * @var EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Sets the underlying SDK library.
   *
   * @return mixed $library_instance
   *   The initialized 3rd party library instance.
   *
   * @throws SocialApiException
   *   If the SDK library does not exist.
   */
  abstract protected function initSdk();

  /**
   * Instantiates a NetworkBase object.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, EntityTypeManagerInterface $entity_type_manager, ConfigFactoryInterface $config_factory) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->entityTypeManager = $entity_type_manager;
    $this->configuration = $entity_type_manager;
    $this->init($config_factory);
  }

  /**
   * {@inheritdoc}
   *
   * By default assume that no action needs to happen to authenticate a request.
   */
  public function authenticate() {
    // Do nothing by default.
  }

  /**
   * Initialize the plugin.
   *
   * This method is called upon plugin instantiation. Instantiates the settings
   * wrapper.
   *
   * @param ConfigFactoryInterface $config_factory
   *   The injected configuration factory.
   *
   * @throws SocialApiException
   *   When the settings are not valid.
   */
  protected function init(ConfigFactoryInterface $config_factory) {
    $definition = $this->getPluginDefinition();
    if (empty($definition['handlers']['settings']['class']) || empty($definition['handlers']['settings']['config_id']) || !class_exists($definition['handlers']['settings']['class'])) {
      throw new SocialApiException('There is no class for the settings. Please check your plugin annotation.');
    }
    $config = $config_factory->get($definition['handlers']['settings']['config_id']);
    $settings = call_user_func($definition['handlers']['settings']['class'] . '::factory', $config);
    if (!$settings instanceof SettingsInterface) {
      throw new SocialApiException('The provided settings class does not implement the expected settings interface.');
    }
    $this->settings = $settings;
  }

  /**
   * {@inheritdoc}
   */
  public function getSdk() {
    if (empty($this->sdk)) {
      $this->sdk = $this->initSdk();
    }
    return $this->sdk;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    /* @var EntityTypeManagerInterface $entity_type_manager */
    $entity_type_manager = $container->get('entity_type.manager');
    /* @var ConfigFactoryInterface $config_factory */
    $config_factory = $container->get('config.factory');
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $entity_type_manager,
      $config_factory
    );
  }

}

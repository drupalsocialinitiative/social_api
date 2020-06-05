<?php

namespace Drupal\social_api\Plugin;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use Drupal\Core\Plugin\PluginBase;
use Drupal\Core\Site\Settings;
use Drupal\social_api\Settings\SettingsInterface;
use Drupal\social_api\SocialApiException;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Base class for Social Network plugins.
 */
abstract class NetworkBase extends PluginBase implements NetworkInterface {

  /**
   * The logger factory.
   *
   * @var \Drupal\Core\Logger\LoggerChannelFactoryInterface
   */
  protected $loggerFactory;

  /**
   * The global site settings.
   *
   * @var \Drupal\Core\Site\Settings
   */
  protected $siteSettings;

  /**
   * The implementer/plugin settings.
   *
   * @var \Drupal\social_api\Settings\SettingsInterface
   */
  protected $settings;

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * The SDK client.
   *
   * @var mixed
   */
  protected $sdk;

  /**
   * Sets the underlying SDK library.
   *
   * @return mixed
   *   The initialized 3rd party library instance.
   *
   * @throws \Drupal\social_api\SocialApiException
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
   * @param array $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Logger\LoggerChannelFactoryInterface $logger_factory
   *   The logger factory.
   * @param \Drupal\Core\Site\Settings $settings
   *   The site settings.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The configuration factory object.
   */
  public function __construct(array $configuration,
                              $plugin_id,
                              array $plugin_definition,
                              LoggerChannelFactoryInterface $logger_factory,
                              Settings $settings,
                              EntityTypeManagerInterface $entity_type_manager,
                              ConfigFactoryInterface $config_factory) {

    parent::__construct($configuration, $plugin_id, $plugin_definition);

    $this->loggerFactory = $logger_factory;
    $this->siteSettings = $settings;
    $this->entityTypeManager = $entity_type_manager;
    $this->configuration = $entity_type_manager;
    $this->init($config_factory);
  }

  /**
   * Initialize the plugin.
   *
   * This method is called upon plugin instantiation. Instantiates the settings
   * wrapper.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The injected configuration factory.
   *
   * @throws \Drupal\social_api\SocialApiException
   *   When the settings are not valid.
   */
  protected function init(ConfigFactoryInterface $config_factory) {
    $definition = $this->getPluginDefinition();
    if (!empty($definition['handlers']['settings']['class']) && !empty($definition['handlers']['settings']['config_id'])) {
      if (!class_exists($definition['handlers']['settings']['class'])) {
        throw new SocialApiException('The specified settings class does not exist. Please check your plugin annotation.');
      }

      $config = $config_factory->get($definition['handlers']['settings']['config_id']);
      $settings = call_user_func($definition['handlers']['settings']['class'] . '::factory', $config);
      if (!$settings instanceof SettingsInterface) {
        throw new SocialApiException('The provided settings class does not implement the expected settings interface.');
      }

      $this->settings = $settings;
    }
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {

    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('logger.factory'),
      $container->get('settings'),
      $container->get('entity_type.manager'),
      $container->get('config.factory')
    );

  }

  /**
   * {@inheritdoc}
   */
  public function getSdk() {
    if (!$this->sdk) {
      $this->sdk = $this->initSdk();
    }

    return $this->sdk;
  }

}

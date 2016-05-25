<?php

/**
 * @file
 * Contains \Drupal\social_api\Plugin\NetworkBase.
 */

namespace Drupal\social_api\Plugin;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Plugin\PluginBase;
use Drupal\social_api\Settings\SettingsInterface;
use Drupal\social_api\SocialApiException;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Base class for Social Network plugins.
 */
abstract class NetworkBase extends PluginBase implements NetworkInterface {
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
  public function __construct(array $configuration, $plugin_id, $plugin_definition, EntityTypeManagerInterface $entity_type_manager) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->entityTypeManager = $entity_type_manager;
    $this->configuration = $entity_type_manager;
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
      $entity_type_manager
    );
  }

}

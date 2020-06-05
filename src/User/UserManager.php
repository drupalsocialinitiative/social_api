<?php

namespace Drupal\social_api\User;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use Drupal\Core\Messenger\MessengerInterface;

/**
 * Manages database related tasks.
 */
abstract class UserManager implements UserManagerInterface {

  /**
   * The Drupal Entity Manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * The Messenger service.
   *
   * @var \Drupal\Core\Messenger\MessengerInterface
   */
  protected $messenger;

  /**
   * The Drupal logger factory.
   *
   * @var \Drupal\Core\Logger\LoggerChannelFactoryInterface
   */
  protected $loggerFactory;

  /**
   * The entity type.
   *
   * @var string
   */
  protected $entityType;

  /**
   * The implementer plugin id.
   *
   * @var string
   */
  protected $pluginId;

  /**
   * Constructor.
   *
   * @param string $entity_type
   *   The entity table.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   Used for loading and creating Social API-related entities.
   * @param \Drupal\Core\Messenger\MessengerInterface $messenger
   *   Used to display messages to user.
   * @param \Drupal\Core\Logger\LoggerChannelFactoryInterface $logger_factory
   *   Used for logging errors.
   */
  public function __construct($entity_type,
                              EntityTypeManagerInterface $entity_type_manager,
                              MessengerInterface $messenger,
                              LoggerChannelFactoryInterface $logger_factory) {

    $this->entityType = $entity_type;
    $this->entityTypeManager = $entity_type_manager;
    $this->messenger = $messenger;
    $this->loggerFactory = $logger_factory;
  }

  /**
   * {@inheritdoc}
   */
  public function getPluginId() {
    return $this->pluginId;
  }

  /**
   * {@inheritdoc}
   */
  public function setPluginId($plugin_id) {
    $this->pluginId = $plugin_id;
  }

  /**
   * {@inheritdoc}
   */
  public function getDrupalUserId($provider_user_id) {

    try {
      /** @var \Drupal\social_api\Entity\SocialApi[] $user */
      $user = $this->entityTypeManager
        ->getStorage($this->entityType)
        ->loadByProperties([
          'plugin_id' => $this->pluginId,
          'provider_user_id' => $provider_user_id,
        ]);

      if (!empty($user)) {
        return current($user)->getUserId();
      }
    }
    catch (\Exception $ex) {
      $this->loggerFactory
        ->get($this->getPluginId())
        ->error('Failed to to query entity. Exception: @message', ['@message' => $ex->getMessage()]);
    }

    return FALSE;
  }

}

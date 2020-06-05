<?php

namespace Drupal\social_api\User;

use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\Core\Session\AccountProxyInterface;
use Drupal\social_api\SocialApiDataHandler;

/**
 * Manages Drupal authentication tasks for Social API.
 */
abstract class UserAuthenticator {

  /**
   * The Drupal Entity Manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * The current Drupal user.
   *
   * @var \Drupal\Core\Session\AccountProxyInterface
   */
  protected $currentUser;

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
   * The Social API user manager.
   *
   * @var \Drupal\social_api\User\UserManagerInterface
   */
  protected $userManager;

  /**
   * The Social API data handler.
   *
   * @var \Drupal\social_api\SocialApiDataHandler
   */
  protected $dataHandler;

  /**
   * Session keys to nullify is user could not be logged in.
   *
   * @var array
   */
  protected $sessionKeys;

  /**
   * The implementer plugin id.
   *
   * @var string
   */
  protected $pluginId;

  /**
   * Constructor.
   *
   * @param \Drupal\Core\Session\AccountProxyInterface $current_user
   *   Used to get current active user.
   * @param \Drupal\Core\Messenger\MessengerInterface $messenger
   *   Used to display messages to user.
   * @param \Drupal\Core\Logger\LoggerChannelFactoryInterface $logger_factory
   *   Used for logging errors.
   * @param \Drupal\social_api\User\UserManagerInterface $user_manager
   *   The Social API user manager.
   * @param \Drupal\social_api\SocialApiDataHandler $data_handler
   *   Used to interact with session.
   */
  public function __construct(AccountProxyInterface $current_user,
                              MessengerInterface $messenger,
                              LoggerChannelFactoryInterface $logger_factory,
                              UserManagerInterface $user_manager,
                              SocialApiDataHandler $data_handler) {

    $this->currentUser = $current_user;
    $this->messenger = $messenger;
    $this->loggerFactory = $logger_factory;
    $this->userManager = $user_manager;
    $this->dataHandler = $data_handler;
  }

  /**
   * Sets the implementer plugin id.
   *
   * This value is used to generate customized logs, messages, and event
   * dispatchers.
   *
   * @param string $plugin_id
   *   The plugin id.
   */
  public function setPluginId($plugin_id) {
    $this->pluginId = $plugin_id;

    $this->userManager->setPluginId($plugin_id);
  }

  /**
   * Gets the implementer plugin id.
   *
   * @return string
   *   The plugin id.
   */
  public function getPluginId() {
    return $this->pluginId;
  }

  /**
   * Sets the session keys to nullify if user could not logged in.
   *
   * @param array $session_keys
   *   The session keys to nullify.
   */
  public function setSessionKeysToNullify(array $session_keys) {
    $this->sessionKeys = $session_keys;
  }

  /**
   * Nullifies session keys if user could not logged in.
   */
  public function nullifySessionKeys() {
    if (!empty($this->sessionKeys)) {
      array_walk($this->sessionKeys, function ($session_key) {
        $this->dataHandler->set($this->dataHandler->getSessionPrefix() . $session_key, NULL);
      });
    }
  }

  /**
   * Returns the current user.
   *
   * @return \Drupal\Core\Session\AccountProxyInterface
   *   The current Drupal user.
   */
  public function currentUser() {
    return $this->currentUser;
  }

}

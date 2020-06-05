<?php

namespace Drupal\social_api\AuthManager;

use Drupal\Core\Config\ImmutableConfig;
use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Defines basic OAuth2Manager to be used by social auth and social post.
 *
 * @package Drupal\social_api
 */
abstract class OAuth2Manager implements OAuth2ManagerInterface {

  /**
   * The service client.
   *
   * @var \League\OAuth2\Client\Provider\AbstractProvider|mixed
   */
  protected $client;

  /**
   * Access token for OAuth2 authentication.
   *
   * @var \League\OAuth2\Client\Token\AccessToken|string|mixed
   */
  protected $accessToken;

  /**
   * Social Auth implementer settings.
   *
   * @var \Drupal\Core\Config\ImmutableConfig
   */
  protected $settings;

  /**
   * The logger factory.
   *
   * @var \Drupal\Core\Logger\LoggerChannelFactoryInterface
   */
  protected $loggerFactory;

  /**
   * The current request.
   *
   * @var \Symfony\Component\HttpFoundation\Request|null
   */
  protected $request;

  /**
   * The user returned by the provider.
   *
   * @var \League\OAuth2\Client\Provider\GenericResourceOwner|array|mixed
   */
  protected $user;

  /**
   * OAuth2Manager Constructor.
   *
   * @param \Drupal\Core\Config\ImmutableConfig $settings
   *   The implementer settings.
   * @param \Drupal\Core\Logger\LoggerChannelFactoryInterface $logger_factory
   *   The logger factory.
   * @param \Symfony\Component\HttpFoundation\Request $request
   *   The current request.
   */
  public function __construct(ImmutableConfig $settings,
                              LoggerChannelFactoryInterface $logger_factory,
                              Request $request = NULL) {

    $this->settings = $settings;
    $this->loggerFactory = $logger_factory;
    $this->request = $request;
  }

  /**
   * {@inheritdoc}
   */
  public function setClient($client) {
    $this->client = $client;
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getClient() {
    return $this->client;
  }

  /**
   * {@inheritdoc}
   */
  public function getAccessToken() {
    return $this->accessToken;
  }

  /**
   * {@inheritdoc}
   */
  public function setAccessToken($access_token) {
    $this->accessToken = $access_token;
    return $this;
  }

}

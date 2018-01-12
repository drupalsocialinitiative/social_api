<?php

namespace Drupal\social_api\AuthManager;

/**
 * Defines an OAuth2Manager Interface.
 *
 * @package Drupal\social_api\OAuth2Manager
 */
interface OAuth2ManagerInterface {

  /**
   * Sets the service client.
   *
   * @param mixed $client
   *   The service client.
   *
   * @return $this
   *   The current object.
   */
  public function setClient($client);

  /**
   * Gets the service client object.
   *
   * @return mixed
   *   The service client object.
   */
  public function getClient();

  /**
   * Authenticates the users by using the access token.
   *
   * @return $this
   *   The current object.
   */
  public function authenticate();

  /**
   * Gets the access token after authentication.
   *
   * @return \League\OAuth2\Client\Token\AccessToken|mixed
   *   The access token.
   */
  public function getAccessToken();

  /**
   * Sets the default access token.
   *
   * @param \League\OAuth2\Client\Token\AccessToken|mixed $access_token
   *   The access token.
   *
   * @return $this
   *   The current object.
   */
  public function setAccessToken($access_token);

}

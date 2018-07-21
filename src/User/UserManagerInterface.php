<?php

namespace Drupal\social_api\User;

/**
 * Interface for database-related tasks.
 */
interface UserManagerInterface {

  /**
   * Gets the implementer plugin id.
   *
   * @return string
   *   The plugin id.
   */
  public function getPluginId();

  /**
   * Sets the implementer plugin id.
   *
   * @param string $plugin_id
   *   The plugin id.
   */
  public function setPluginId($plugin_id);

  /**
   * Gets the Drupal user id based on the provider user id.
   *
   * @param string $provider_user_id
   *   User's id on provider.
   *
   * @return int|false
   *   The Drupal user id if it exists.
   *   False otherwise.
   */
  public function getDrupalUserId($provider_user_id);

}

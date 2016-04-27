<?php

/**
 * @file
 * Contains \Drupal\social_autopost\Annotation\Network.
 */

namespace Drupal\social_api\Annotation;

use Drupal\Component\Annotation\Plugin;

/**
 * Defines a Social Network item annotation object.
 *
 * @see \Drupal\social_autopost\Plugin\NetworkManager
 * @see plugin_api
 *
 * @Annotation
 */
class Network extends Plugin {

  /**
   * The plugin ID.
   *
   * @var string
   */
  public $id;

  /**
   * The label of the plugin.
   *
   * @var \Drupal\Core\Annotation\Translation
   *
   * @ingroup plugin_translatable
   */
  public $label;

  /**
   * The type of the plugin
   *
   * @var string
   */
  public $type;

  /**
   * A list of extra handlers.
   *
   * @var array
   *
   * @todo Check the entity type plugins to copy from.
   */
  public $handlers = array();

}

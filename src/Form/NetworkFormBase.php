<?php

/**
 * @file
 * Contains \Drupal\social_api\Form\NetworkFormBase.
 */
namespace Drupal\social_api\Form;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Utility\Error;
use Drupal\social_api\Plugin\NetworkInterface;
use Drupal\social_api\SocialApiException;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class NetworkFormBase.
 *
 * @package Drupal\social_api\Form
 */
abstract class NetworkFormBase extends ConfigFormBase {

  /**
   * The network plugin.
   *
   * @var NetworkInterface
   */
  protected $network;

  /**
   * Constructs a \Drupal\system\ConfigFormBase object.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The factory for configuration objects.
   *
   * @param NetworkInterface $network
   */
  public function __construct(ConfigFactoryInterface $config_factory, NetworkInterface $network = NULL) {
    parent::__construct($config_factory);
    $this->network = $network;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    $network = NULL;
    //$network = $container->get('plugin.network.manager')->createInstance(static::getNetworkMachineName());
    try {
      $network = $container->get('plugin.network.manager')
        ->createInstance(static::getNetworkMachineName());
    }
    catch (SocialApiException $exception) {
      $message = '%type: @message in %function (line %line of %file).';
      $variables = Error::decodeException($exception);

      $container
        ->get('logger.factory')
        ->get('social_autopost')
        ->log('warning', $message, $variables);
    }
    var_dump($network);
    return new static($container->get('config.factory'), $network);
  }

  /**
   * Get the machine name of the network implementing the form.
   *
   * @return string
   *   The plugin ID.
   *
   * @throws SocialApiException
   *   When the method is not implemented.
   */
  protected static function getNetworkMachineName() {
    throw new SocialApiException('This method needs to be implemented.');
  }

}

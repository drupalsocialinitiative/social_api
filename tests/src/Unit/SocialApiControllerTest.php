<?php

namespace Drupal\Tests\social_api\Unit;

use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\social_api\Controller\SocialApiController;
use Drupal\social_api\Plugin\NetworkManager;
use Drupal\Tests\UnitTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Tests for the SocialApiController.
 *
 * @coversDefaultClass \Drupal\social_api\Controller\SocialApiController
 * @group SocialAPIImplementer
 */
class SocialApiControllerTest extends UnitTestCase {

  use StringTranslationTrait;

  /**
   * The tested Social API Controller.
   *
   * @var \Drupal\social_api\Controller\SocialApiController
   */
  protected $controller;

  /**
   * The Social API Network Manager.
   *
   * @var \Drupal\social_api\Plugin\NetworkManager|\PHPUnit_Framework_MockObject_MockObject
   */
  protected $networkManager;

  /**
   * {@inheritdoc}
   */
  public function setUp() {
    parent::setUp();

    // The Drupal container must be initialized for string translation to work.
    /** @var \Symfony\Component\DependencyInjection\ContainerInterface|\PHPUnit_Framework_MockObject_MockObject $container */
    $container = $this->createMock(ContainerInterface::class);
    \Drupal::setContainer($container);

    $this->networkManager = $this->getMockBuilder(NetworkManager::class)
      ->disableOriginalConstructor()
      ->getMock();

    $this->controller = $this->getMockBuilder(SocialApiController::class)
      ->setConstructorArgs([$this->networkManager])
      ->setMethods(NULL)
      ->getMock();
  }

  /**
   * Tests that only integrations for Social Auth are rendered.
   */
  public function testGetIntegrationsForSocialAuth() {

    // When calling SocialApiController::integrations, getDefinitions should be
    // called once and return a non-empty array.
    $this->networkManager->expects($this->once())
      ->method('getDefinitions')
      ->willReturn($this->getDefinitions());

    // The #rows property in the expected render array must be an array of the
    // Social Auth integrations (without the type key) and converted to a
    // non-associative array.
    $rows = [];
    foreach ($this->getSocialAuthDefinitions() as $integration) {
      $rows[] = [
        $integration['id'],
        $integration['social_network'],
      ];
    }

    $expected = [
      '#theme' => 'table',
      '#header' => [
        $this->t('Module'),
        $this->t('Social Network'),
      ],
      '#rows' => $rows,
      '#empty' => $this->t('There are no social integrations enabled.'),
    ];

    $integrations = $this->controller->integrations('social_auth');

    $this->assertEquals($expected, $integrations);
  }

  /**
   * Returns the mocked Network plugin definitions.
   *
   * @return array
   *   The Network plugin definitions array.
   */
  protected function getDefinitions() {
    return array_merge($this->getSocialAuthDefinitions(),
                       $this->getSocialPostDefinitions());
  }

  /**
   * Returns the mocked Network plugin definitions for Social Auth.
   *
   * @return array
   *   The Network plugin definitions array.
   */
  protected function getSocialAuthDefinitions() {
    return [
      [
        'type' => 'social_auth',
        'id' => 'social_auth_google',
        'social_network' => 'Google',
      ],
      [
        'type' => 'social_auth',
        'id' => 'social_auth_facebook',
        'social_network' => 'Facebook',
      ],
    ];
  }

  /**
   * Returns the mocked Network plugin definitions for Social Post.
   *
   * @return array
   *   The Network plugin definitions array.
   */
  protected function getSocialPostDefinitions() {
    return [
      [
        'type' => 'social_post',
        'id' => 'social_post_google',
        'social_network' => 'Google',
      ],
    ];
  }

}

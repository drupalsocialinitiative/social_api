<?php

// Don't forget namespacing.
namespace Drupal\Tests\social_api\Unit;

// You must ordered your imports in alphabetical order.
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\social_api\Controller\SocialApiController;
use Drupal\social_api\Plugin\NetworkManager;
use Drupal\Tests\UnitTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Write a test (meaning a method in the following class) per case.
 *
 * Remember that the method you're testing MUST NOT be mocked. Otherwise, why
 * do you bother testing? As an example, I'll add an example for the
 * integrations method in SocialApiController.
 *
 * Ask yourself
 *
 * 1) What should this method do? What am I expecting it to do?
 * Answer: This method should return a Drupal rendering array that is generated
 * using the array of Network plugins for the given type (social_auth or
 * social_post).
 *
 * 2) Does this method depends on calling other methods?
 * Answer: Yes, it depends on the Network plugins array returned by the method
 * getDefinitions() in NetworkManager. But getDefinitions() is not the method
 * we want to test, so we can mock it and assume that it's returning the value
 * we expect. Again, I'm mocking helper functions/methods that are called inside
 * the method I'm testing. I'm not mocking the method I want to test.
 *
 * 3) What are the paths I should consider?
 * A path is a different place your code can go to depending on a value. It is
 * more clear with an example
 *
 * $x = helperFunction();
 * if ($x) {
 *   doSomething();
 *   return 'a';
 * }
 * else {
 *   doSomethingElse();
 *   return 'b';
 * }
 *
 * I can see that there are two paths here. One of them is the path inside the
 * if. The other is the one inside the else. Notice that the path your test
 * takes depends on the value of $x which depends on a helper function. You
 * must write a test per path. This implies that you must change the value
 * returned by the mocked helperFunction() to go through the path you're
 * interested in.
 *
 * In the specific case of SocialApi::integrations, I don't see more than one
 * single path.
 *
 * 4) What are the possible cases?
 * Even though there is one single path, there's something else you should
 * notice. What you get from integrations() depends on the value you passed as
 * an argument and on the value returned by getDefinitions. You must write a test
 * for each case. Example:
 *   * When getting no definitions at all, the render array mush look like X
 *   * When getting definitions for both social_auth and social_post, but
 *     my argument $type = 'social_auth', the render array must look like Y.
 *
 * @coversDefaultClass \Drupal\social_api\Controller\SocialApiController
 * @group SocialAPIImplementer
 */
class SocialApiControllerTest extends UnitTestCase {

  use StringTranslationTrait;

  /**
   * The tested Social API Controller.
   *
   * I'm making this a property because it is used all over the file.
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
   *
   * This method is called before running each test in this file. Basically,
   * I want to have a "clear" instance of my mocked SocialApiController in each
   * test. This method sets ups the desired mocked class.
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
   * {@inheritdoc}
   *
   * This method is called after running each test in this file. In this case,
   * I don't need to unset the property controller since it would be
   * re-initialized by setUp anyways. However, this is an example of how you
   * might use tearDown if you want to undo some changes after each test.
   */
  public function tearDown() {
    unset($this->controller);
  }

  /**
   * Tests for class SocialApiController.
   *
   * To be honest, the only cases in which I will check if a method exists is
   * for classes that implement an interface or inherit from an abstract class.
   * Those are the only cases in which I believe checking for something like
   * this is relatively useful.
   */
  public function testSocialApiController() {
    $this->assertTrue(
      method_exists($this->controller, 'create'),
      'SocialApiController class does not implements create function/method'
    );
    $this->assertTrue(
      method_exists($this->controller, 'integrations'),
      'SocialApiController class does not implements integrations function/method'
    );
  }

  /**
   * Tests that only integrations for Social Auth are rendered.
   *
   * This is the type of tests that are more useful. I'm actually testing if
   * a method is behaving as I expect. In this case, given that I have
   * integrations for both Social Auth and Social Post, I expect integrations
   * to return a render array containing only the integrations for Social Auth.
   */
  public function testGetIntegrationsForSocialAuth() {

    // When calling SocialApiController::integrations, I'm expecting
    // getDefinitions to be called once and return a non-empty array.
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

    // Now I call the method I'm interested in testing.
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

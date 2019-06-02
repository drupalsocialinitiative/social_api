<?php


use Drupal\Tests\UnitTestCase;
use PHPUnit\Framework\TestCase;
use Drupal\social_api\Utility\SocialApiImplementerInstaller;
use Drupal\social_api\Annotation\Network;
use Drupal\Drupal\social_api\User\UserAuthenticator;
use Drupal\social_api\SocialApiDataHandler;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
// Controller
use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\social_api\Plugin\NetworkManager;
use Drupal\social_api\Controller\SocialApiController;
use Drupal\Core\Plugin\DefaultPluginManager;
use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\DependencyInjection\ContainerBuilder;
//SocialApiException
use Drupal\social_api\SocialApiException;
use Drupal\social_api\User\UserManagerInterface;
use Drupal\social_api\AuthManager\OAuth2Manager;
use Drupal\social_api\AuthManager\OAuth2ManagerInterface;



class SocialApiTest extends UnitTestCase {
  /**
   * Checks the library required by an implementer.
   *
   * @param string $machine_name
   *   The module machine name.
   *
   * @param string $name
   *   The module name.
   * @param string $library
   *   The library machine name.
   * @param float $min_version
   *   The min version required.
   * @param float $max_version
   *   The max version required.
   *
   * @return array
   *   Requirements messages.
   */

  public $machine_name;
  public $name;
  public $library;
  public $min_version;
  public $max_version;
  protected $config;
  protected $session;
  private $networkManager;
  protected $form = array();
  protected $namespaces;
  protected $module_handler;
  protected $cache_backend;
  public $type = "";
  public $container;
  private $networkManagers;
  protected $client;
  public $fff = 'akaka';



  /**
   * __construct function
   */
  public function __construct() {
       parent::__construct();
   }


  /**
   * {@inheritdoc}
   */

  public function setUp() {
    // enable any other required module
    parent::setUp();
    // $this->socialPostEntityDeleteForm = $this->getMock($SocialPostEntityDeleteForm::class, ['getRedirectUrl']);
  }

  /**
   * test for class OAuth2Manager
   */

  public function testOAuth2Manager () {
    // assertion to check if the file exists
    $this->assertFileExists('../drupal8/modules/social_api/src/AuthManager/OAuth2Manager.php');

    $abstractClass = 'Drupal\social_api\AuthManager\OAuth2Manager';

    // checking for correct getClient and setClient methods
     $mockAbstractClass = $this->getMockBuilder($abstractClass)
       ->setMethods(array('setClient','getClient'))
       ->getMockForAbstractClass();
       // $mock->expects($this->once())
       //          ->method('setClient')
       //          ->with($this->equalTo('something'));

     $result = $mockAbstractClass->setClient(12345);
     $this->assertEquals($result, $mockAbstractClass->getClient());

     // checking for correct getAccessToken and getAccessToken methods
      $mockAbstractClass = $this->getMockBuilder($abstractClass)
        ->setMethods(array('setAccessToken','getAccessToken'))
        ->getMockForAbstractClass();
        // $mock->expects($this->once())
        //          ->method('setClient')
        //          ->with($this->equalTo('something'));

      $result = $mockAbstractClass->setAccessToken(12345);
      $this->assertEquals($result, $mockAbstractClass->getAccessToken());

      //check for functions if present
      $this->assertTrue(
        method_exists($mockAbstractClass, 'setClient'),
          'OAuth2ManagerInterface does not have setClient function/method'
        );
      $this->assertTrue(
        method_exists($mockAbstractClass, 'getClient'),
          'OAuth2ManagerInterface does not have getClient function/method'
        );
      $this->assertTrue(
        method_exists($mockAbstractClass, 'getAccessToken'),
          'OAuth2ManagerInterface does not have getAccessToken function/method'
        );
      $this->assertTrue(
        method_exists($mockAbstractClass, 'setAccessToken'),
          'OAuth2ManagerInterface does not have setAccessToken function/method'
        );
  }


  /**
   * test for class OAuth2ManagerInterface
   */

  public function testOAuth2ManagerInterface () {
    // assertion to check if the file exists
    $this->assertFileExists('../drupal8/modules/social_api/src/AuthManager/OAuth2ManagerInterface.php');

    $collection = $this->createMock(OAuth2ManagerInterface::class);
    $this->assertNull($collection->getClient());
    $this->assertTrue(
      method_exists($collection, 'setClient'),
        'OAuth2ManagerInterface does not have setClient function/method'
      );
    $this->assertTrue(
      method_exists($collection, 'getClient'),
        'OAuth2ManagerInterface does not have getClient function/method'
      );
    $this->assertTrue(
      method_exists($collection, 'getAccessToken'),
        'OAuth2ManagerInterface does not have getAccessToken function/method'
      );
    $this->assertTrue(
      method_exists($collection, 'setAccessToken'),
        'OAuth2ManagerInterface does not have setAccessToken function/method'
      );
    $this->assertTrue(
    method_exists($collection, 'getAuthorizationUrl'),
        'OAuth2ManagerInterface does not have getAuthorizationUrl function/method'
      );
    $this->assertTrue(
      method_exists($collection, 'getState'),
        'OAuth2ManagerInterface does not have getState function/method'
      );
    $this->assertTrue(
      method_exists($collection, 'getUserInfo'),
        'OAuth2ManagerInterface does not have getUserInfo function/method'
      );

  }

  /**
   * test for class SocialApiController
   */

  public function testSocialApiController () {

    // assertion to check if the file exists
    $this->assertFileExists('../drupal8/modules/social_api/src/Controller/SocialApiController.php');

    /**
     * @var PHPUnit_Framework_MockObject_MockObject
     */
    $this->namespaces = $this->getMock(Traversable::class);
    $this->cache_backend = $this->getMock(CacheBackendInterface::class);
    $this->module_handler = $this->getMock(ModuleHandlerInterface::class);

    // passing parameters for constructor method in NetWorkManager Class
    // $this->networkManagers = new NetworkManager($this->namespaces, $this->cache_backend, $this->module_handler);

    $this->networkManager = $this->getMock(NetworkManager::class,
                                     array('read'),
                                     array($this->namespaces, $this->cache_backend, $this->module_handler));
   /**
    * @var PHPUnit_Framework_MockObject_MockObject
    */
   $collection = $this->getMock(SocialApiController::class,
                            array('read'),
                            array($this->networkManager));
   // $collection = new SocialApiController($this->networkManagers);

   // Initialization done, tests goes here.

  }

  /**
   * Test for class SocialApiException
   */

  public function testSocialApiException () {
    // assertion to check if the file exists
    $this->assertFileExists('../drupal8/modules/social_api/src/SocialApiException.php');
    $collection = new SocialApiException();
  }

  /**
   * Test for class SocialApiImplementerInstaller
   */

  public function testSocialApiImplementerInstaller () {
    // assertion to check if the file exists
    $this->assertFileExists('../drupal8/modules/social_api/src/Utility/SocialApiImplementerInstaller.php');

    $collection = new SocialApiImplementerInstaller();
    // mock object to our interface
    $mock = $this->getMock('Drupal\social_api\Utility\SocialApiImplementerInstaller');
    // check if the mock object belongs to our interface
    $this->assertTrue($mock instanceof SocialApiImplementerInstaller);

  }

  /**
   * Test for interface UserManagerInterface
   */

   public function testUserManagerInterface () {
     // assertion to check if the file exists
     $this->assertFileExists('../drupal8/modules/social_api/src/User/UserManagerInterface.php');
   }

   /**
    * Test for class UserManager
    */

   public function testUserManager () {
     // assertion to check if the file exists
     $this->assertFileExists('../drupal8/modules/social_api/src/User/UserManager.php');
   }

   /**
    * Test for class UserAuthenticator
    */

   public function testUserAuthenticator () {
     // assertion to check if the file exists
     $this->assertFileExists('../drupal8/modules/social_api/src/User/UserAuthenticator.php');
   }

   /**
    * test for class Network
    */

   public function testNetwork () {
     // assertion to check if the file exists
     $this->assertFileExists('../drupal8/modules/social_api/src/Annotation/Network.php');
     // mock object to our interface and checking for instances
     $net = new Network($this->form);
     $this->assertTrue($net instanceof Network);
     $WFC = new Network($this->form);
     $WF =  $this->getMock('Drupal\social_api\Annotation\Plugin');
     $this->assertTrue((new ReflectionClass($WF))->getParentClass()->getName() != $WFC);
     // assertArray and other methods are not working here.
   }

   /**
    * Test for class SettingsBase
    */

   public function testSettingsBase () {
     // assertion to check if the file exists
     $this->assertFileExists('../drupal8/modules/social_api/src/Settings/Settingsbase.php');
   }

   /**
    * Test for class SettingsInterface
    */

   public function testSettingsInterface () {
     // assertion to check if the file exists
     $this->assertFileExists('../drupal8/modules/social_api/src/Settings/SettingsInterface.php');
   }

   /**
    * Test for class NetworkBase
    */

   public function testNetworkBase () {
     // assertion to check if the file exists
     $this->assertFileExists('../drupal8/modules/social_api/src/Plugin/NetworkBase.php');
   }

   /**
    * Test for class NetworkInterface
    */

   public function testNetworkInterface () {
     // assertion to check if the file exists
     $this->assertFileExists('../drupal8/modules/social_api/src/Plugin/NetworkInterface.php');
   }

   /**
    * Test for class NetworkManager
    */

   public function testNetworkManager () {
     // assertion to check if the file exists
     $this->assertFileExists('../drupal8/modules/social_api/src/Plugin/NetworkManager.php');
   }

  }

 ?>

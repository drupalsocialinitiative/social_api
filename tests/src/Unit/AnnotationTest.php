<?php

use Drupal\social_api\Annotation\Network;
use Drupal\Tests\UnitTestCase;

/**
 * Defines a Social Network item annotation object.
 *
 * @Annotation
 */
class AnnotationTest extends UnitTestCase {

  /**
   * Define __construct function.
   */
  public function __construct() {
    parent::__construct();
  }

  /**
   * {@inheritdoc}
   */
  public function setUp() {
    parent::setUp();
  }

  /**
   * Tests for class Network.
   */
  public function testNetwork() {
    $network = $this->createMock(Network::class);
    $this->assertInternalType('array', $network->handlers);
  }

}

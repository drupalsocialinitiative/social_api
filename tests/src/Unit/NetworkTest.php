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
   * Tests for class Network.
   */
  public function testNetwork() {
    $network = $this->createMock(Network::class);
    $this->assertInternalType('array', $network->handlers);
  }

}

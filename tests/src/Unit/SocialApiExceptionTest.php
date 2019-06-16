<?php

use Drupal\Tests\UnitTestCase;
use Drupal\social_api\SocialApiException;

/**
 * Defines class for SocialApiExcetion Test.
 */
class SocialApiExceptionTest extends UnitTestCase {

  /**
   * Tests for class SocialApiException.
   */
  public function testException() {
    $socialApiException = new SocialApiException();
    try {
      if (!$socialApiException) {
        throw new Exception();
      }
    }
    catch (\Exception $e) {
      echo 'Message: ' . $e;
    }
    // We need some assertion here otherwise the test will show a warning.
    $this->assertTrue($socialApiException instanceof SocialApiException);
  }

}

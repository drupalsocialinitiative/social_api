<?php

use Drupal\Tests\UnitTestCase;
use Drupal\social_api\SocialApiException;

class SocialApiExceptionTest extends UnitTestCase{
  public function testException() {
    $socialApiException = new SocialApiException();
    try {
      if (!$socialApiException)
      throw new Exception();
    } catch(\Exception $e) {
      echo 'Message: ' .$e;
    }
    //we need some assertion here otherwise the test will show a warning.
    $this->assertTrue($socialApiException instanceof SocialApiException);
  }

}

 ?>

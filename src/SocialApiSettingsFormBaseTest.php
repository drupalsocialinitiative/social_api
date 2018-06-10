<?php

namespace Drupal\social_api;

use Drupal\Tests\social_api\Functional\SocialApiTestBase;

/**
 * Provides a test case for functional Social Api settings form tests.
 *
 * @deprecated
 *
 * @TODO: Remove this file when all implementers update their parent test base class.
 * @see https://www.drupal.org/project/social_api/issues/2978457
 */
abstract class SocialApiSettingsFormBaseTest extends SocialApiTestBase {

  /**
   * An alias for 'provider' property.
   *
   * @var string
   */
  protected  $socialNetwork;

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    $this->adminUserPermissions = [
      'administer social api authentication',
      'administer social api autoposting',
    ];

    parent::setUp();

    $this->provider = $this->socialNetwork;
  }

  /**
   * Tests that module is available in social api list.
   */
  public function testIsAvailableInIntegrationList() {
    $this->checkIsAvailableInIntegrationList();
  }

  /**
   * Tests configuration page.
   *
   * @throws \Behat\Mink\Exception\ElementNotFoundException
   *   If element is not found.
   */
  public function testSettingsPage() {
    $this->checkPermissionForSettingsPage();
  }

  /**
   * Tests module settings form submission.
   */
  public function testSettingsFormSubmission() {
    $this->checkSettingsFormSubmission();
  }

}

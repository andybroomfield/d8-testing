<?php
// tests/src/Functional/PageListTest.php

namespace Drupal\Tests\tdd_bhcc\Functional;

use Drupal\Tests\BrowserTestBase;

class PageListTest extends BrowserTestBase {

  protected static $modules = ['tdd_bhcc'];

  /**
   * Test the Listing Page Exists
   */
  public function testListingPageExists() {
    $this->drupalGet('pages');
    $this->assertSession()->statusCodeEquals(200);
  }

}

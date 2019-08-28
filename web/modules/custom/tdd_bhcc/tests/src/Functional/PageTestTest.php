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

  /**
   * Test only published pages are shown
   */
  public function testOnlyPublishedPagesAreShown() {

    // Given that a have a mixture of published and unpublished pages, as well
    // as other types of content.
    $this->drupalCreateContentType(['type' => 'article']);

    $this->drupalCreateNode(['type' => 'page', 'status' => TRUE]);     // NID : 1
    $this->drupalCreateNode(['type' => 'article']);                    // NID : 2
    $this->drupalCreateNode(['type' => 'page', 'status' => FALSE]);    // NID : 3
    $this->drupalCreateNode(['type' => 'page', 'status' => TRUE]);     // NID : 4
    $this->drupalCreateNode(['type' => 'article', 'status' => TRUE]);  // NID : 5
    $this->drupalCreateNode(['type' => 'page', 'status' => FALSE]);    // NID : 6
    $this->drupalCreateNode(['type' => 'article', 'status' => FALSE]); // NID : 7

    // When I view the page.
    $result = views_get_view_result('pages');
    $nids = array_column($result, 'nid');

    // Then I should only see the published pages.
    // This will be an array of Node IDs, from the test
    // Correct is 1, 4
    $this->assertEquals([1, 4], $nids);
  }

}

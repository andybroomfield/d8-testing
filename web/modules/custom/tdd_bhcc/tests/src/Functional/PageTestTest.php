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
    $this->drupalGet('tdd-bhcc-content');
    $this->assertSession()->statusCodeEquals(200);
  }

  /**
   * Test only published pages are shown
   */
  public function testOnlyPublishedPagesAreShown() {

    // Given that a have a mixture of published and unpublished pages, as well
    // as other types of content.
    $this->drupalCreateContentType(['type' => 'article']);

    // Create some nodes
    $this->drupalCreateNode(['type' => 'tdd_bhcc', 'status' => TRUE]);     // NID : 1
    $this->drupalCreateNode(['type' => 'article']);                    // NID : 2
    $this->drupalCreateNode(['type' => 'tdd_bhcc', 'status' => FALSE]);    // NID : 3
    $this->drupalCreateNode(['type' => 'tdd_bhcc', 'status' => TRUE]);     // NID : 4
    $this->drupalCreateNode(['type' => 'article', 'status' => TRUE]);  // NID : 5
    $this->drupalCreateNode(['type' => 'tdd_bhcc', 'status' => FALSE]);    // NID : 6
    $this->drupalCreateNode(['type' => 'article', 'status' => FALSE]); // NID : 7

    // When I view the page.
    $result = views_get_view_result('tdd_bhcc_view');
    $nids = array_column($result, 'nid');

    // Then I should only see the published pages.
    // This will be an array of Node IDs, from the test
    // Correct is 1, 4
    $this->assertEquals([1, 4], $nids);
  }

  /**
   * Test results are in alphabetical order
   */
  public function testResultsAreOrderedAlphabetically() {

    // Given I have multiple nodes with different titles.
    // Have added created dates out of order to test that dates do not sort the order
    $this->drupalCreateNode(['type' => 'tdd_bhcc', 'title' => 'AAA', 'created' => '-60 days']);
    $this->drupalCreateNode(['type' => 'tdd_bhcc', 'title' => 'DDD', 'created' => 'now']);
    $this->drupalCreateNode(['type' => 'tdd_bhcc', 'title' => 'CCC', 'created' => '-20 days']);
    $this->drupalCreateNode(['type' => 'tdd_bhcc', 'title' => 'BBB', 'created' => '-10 days']);

    // When I view the pages list.
    $nids = array_column(views_get_view_result('tdd_bhcc_view'), 'nid');

    // Then I should see pages in the correct order.
    $this->assertEquals([1, 4, 3, 2], $nids);

  }

  /**
   * Test results are in date order when title is the same
   */
  public function testResultsAreOrderedByDateWhenSameTitle() {

    // Given I have multiple nodes with Same titles.
    // With created at different dates
    $this->drupalCreateNode(['type' => 'tdd_bhcc', 'title' => 'Same Title', 'created' => '-60 days']);
    $this->drupalCreateNode(['type' => 'tdd_bhcc', 'title' => 'Same Title', 'created' => 'now']);
    $this->drupalCreateNode(['type' => 'tdd_bhcc', 'title' => 'Same Title', 'created' => '-10 days']);
    $this->drupalCreateNode(['type' => 'tdd_bhcc', 'title' => 'Same Title', 'created' => '-20 days']);

    // When I view the pages list.
    $nids = array_column(views_get_view_result('tdd_bhcc_view'), 'nid');

    // Then I should see pages in the correct (date) order.
    $this->assertEquals([2, 3, 4, 1], $nids);

  }

}

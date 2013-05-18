Feature: Making the 10n'th comment in an existing thread
    In order to get participation marks
    As a student
    I want to be able to comment in a forum post

    Scenario: Making the 10th comment in an existing thread
    Given there is an existing thread "Test" with 9 comments
    When I enter another comment
    And I press the "Post" button
    Then there should be 10 comments on page "1"
    And there should be no link to page "2"

    Scenario: How the thread listing should look like after the 10th comment is made
    Given there is an existing thread "Test" with 9 comments
    When I enter another comment
    And I press the "Post" button
    And I press the "Test" link
    Then the word "Page:" should not appear
    And there should be no link to page "2"

    Scenario: Going to a non-existent second page should give us the first page
    Given there is an existing thread "Test" with 9 comments
    When I enter another comment
    And I press the "Post" button
    And I press the "Test" link
    And the word "Page:" appears
    And there is a link to page "2"
    And I press the link "2"
    Then I should see 9 comments on the page


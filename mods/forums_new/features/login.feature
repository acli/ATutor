Feature: Log in to ATutor
    In order to get credit for what I do
    As a student
    I want to identify myself to the system

    Scenario: Going directly to the home page
    Given I am on the home page
    And the user "test" with password "#@!$%'" exists
    When I enter "test" in the "Login Name or Email" field
    and I enter "#@!$%" in the "Password" field
    and I press the "Login" button
    Then the "Log-out" link should be on the screen

Feature: Diffy example
  Provide simple example how to use
  Diffy in Behat.

  @javascript
  Scenario: Create set of screenshots, send to Diffy and run comparison

    # Create screenshots from two pages and send them to Diffy
    Given I am on "/"
    Then I take screenshots for all breakpoints

    Given I am on "/wordpress"
    Then I take screenshots for all breakpoints

    Then Send screenshots to diffy with name "first test"

    # Create screenshots from two pages with different breakpoints and send them to Diffy
    Given I am on "/"
    When I resize window to "1200"
    Then I take screenshot

    Given I am on "/wordpress"
    When I resize window to "600"
    Then I take screenshot
    Then Send screenshots to diffy with name "second test"

    # Create Diffy comparison between two sets of screenshots created in the previous steps of the test
    Then Create diffy comparison


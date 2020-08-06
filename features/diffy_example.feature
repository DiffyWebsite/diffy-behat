Feature: Diffy example
  Provide simple example how to use
  Diffy in Behat.

  @javascript
  Scenario: Create set of screenshots, send to Diffy and run comparison

    # Create screenshots from another two pages and send them to Diffy
    Given I am on "/wiki/Main_Page"
    When I resize window to "1200"
    Then I take screenshot for diffy with URI "/test"

    Given I am on "/wiki/Russell_family_(Passions)"
    When I resize window to "600"
    Then I take screenshot for diffy with URI "/"
    Then Send screenshots to diffy with name "first test"

    # Create screenshots from another two pages and send them to Diffy
    Given I am on "/wiki/Portal:Biography"
    When I resize window to "1200"
    Then I take screenshot for diffy with URI "/test"

    Given I am on "/wiki/Wikipedia:Contents/Portals#Natural_and_physical_sciences"
    When I resize window to "600"
    Then I take screenshot for diffy with URI "/"
    Then Send screenshots to diffy with name "second test"

    # Create Diffy comparison between two sets of screenshots created in the previous steps of the test
    Then Create diffy comparison


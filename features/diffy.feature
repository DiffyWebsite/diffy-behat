Feature: Diffy
  An example how to use diffy in behat

  Scenario: Login to Diffy
    Given I login with apiKey "cb467e043db41d1dd3196607790adbe6"
    Given I get diff list for project "30"
    Then I should see "Successfully logged in"

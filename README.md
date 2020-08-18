# Use diffy with behat

## Usage

Here is an example how your behat integration could look like:
```
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

    Given I am on "/about-us"
    When I resize window to "600"
    Then I take screenshot
    Then Send screenshots to diffy with name "second test"

    # Create Diffy comparison between two sets of screenshots created in the previous steps of the test
    Then Create diffy comparison
```    

## Install dependencies
run `composer install`

## Install docker
We are using docker for run selenium.
If you already has own selenium installation just skip these steps.

### Use this link to install docker: 
https://docs.docker.com/engine/install/ubuntu/

### Run selenium in docker
Run `docker-compose -f docker/docker-compose.yml up`

## Configure behat settings
Edit ./behat.yml

### Configure parametrs:

```
parameters:
      projectId: {diffyProjectID}
      apiKey: {diffyApiKey}
      screenshotsDir: screenshots
      breakpoints: [640, 1200]
      windowHeight: 2000
```

*Where:* 

Parameter      | Description 
-------------- |:-------------
projectId      | Project ID from Diffy
apiKey         | Api key from Diffy (You can get it on this page: https://app.diffy.website/#/keys)
screenshotsDir | Path to temporary folder where will be stored screenshots
breakpoints    | Breakpoints array (they should be available in the Diffy project)
windowHeight   | Height of screenshots 


### Configure mink extension:
```
Behat\MinkExtension\Extension:
      base_url: https://diffy.website
      goutte: ~
      javascript_session: selenium2
      browser_name: chrome
      selenium2:
        wd_host: "http://localhost:4444/wd/hub"
```

*Where:* 

Parameter         | Description 
----------------- |:-------------
base_url          | Base URL of the tested site
browser_name      | chrome - by default in docker-compose.yml installed only chrome browser. But you can uncomment other browsers.
selenium2:wd_host | If you are using own selenium installation, please specify here your selenium url. 


## Run behat test
Run `php ./bin/behat`

After the test is being completed, two screenshots one comparison will be created on Diffy.


## Down docker with selenium

Run `docker-compose -f docker/docker-compose.yml down`

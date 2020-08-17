# Use diffy with behat

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
apiKey         | Api key from Diffy
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

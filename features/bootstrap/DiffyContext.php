<?php

use Behat\MinkExtension\Context\MinkContext;
use Diffy\Diffy;
use Diffy\Diff;
use Diffy\Screenshot;

/**
 * Features context.
 */
class DiffyContext extends MinkContext
{
    const WINDOW_PADDING = 8;
    private $screenshots = [];
    private $projectId;
    private $apiKey;
    private $screenshotsDir;
    private $breakpoints;
    private $windowHeight;
    private $createdScreenshotIds = [];
    private $createdDiffIds = [];

    /**
     * Initializes context.
     * Every scenario gets its own context object.
     *
     * @param array $parameters context parameters (set them up through behat.yml)
     */
    public function __construct(array $parameters)
    {
        $this->projectId = isset($parameters['projectId']) ? $parameters['projectId'] : null;
        $this->apiKey = isset($parameters['apiKey']) ? $parameters['apiKey'] : null;
        $this->screenshotsDir = isset($parameters['screenshotsDir']) ? $parameters['screenshotsDir'] : null;
        $this->breakpoints = isset($parameters['breakpoints']) ? $parameters['breakpoints'] : [];
        $this->windowHeight = isset($parameters['windowHeight']) ? $parameters['windowHeight'] : 1000;
    }

    /**
     * @Then  /^I resize window to "([^"]*)"$/
     */
    public function iResizeWindowTo($breakpoint)
    {
        $this->getSession()->resizeWindow((int)$breakpoint + self::WINDOW_PADDING, $this->windowHeight);
    }

    /**
     * Take screenshots for all breakpoints provided in behat.yml
     *
     * @Then /^I take screenshots for all breakpoints$/
     */
    public function iTakeScreenshotsForAllBreakpoints()
    {
        $driver = $this->getSession()->getDriver();
        if (!($driver instanceof \Behat\Mink\Driver\Selenium2Driver)) {
            return;
        }

        foreach ($this->breakpoints as $breakpoint) {
            $this->iResizeWindowTo($breakpoint);
            $this->iTakeScreenshot();
        }
    }

    /**
     * Take one screenshot.
     *
     * @Then /^I take screenshot$/
     */
    public function iTakeScreenshot()
    {
        $driver = $this->getSession()->getDriver();
        if (!($driver instanceof \Behat\Mink\Driver\Selenium2Driver)) {
            return;
        }

        $path = str_replace($this->getMinkParameter('base_url'), '', $this->getSession()->getCurrentUrl());

        $screenshot = $this->getSession()->getDriver()->getScreenshot();
        // Array: [0] => width, [1] => height
        $screenshotInfo = getimagesizefromstring($screenshot);
        $filename = sprintf('%s__%s__%s', time(), urlencode($path), $screenshotInfo[0]);
        file_put_contents("$this->screenshotsDir/$filename.png", $screenshot);

        $this->screenshots[] = [
            'file' => fopen("$this->screenshotsDir/$filename.png", 'r'),
            'url' => $path,
            'breakpoint' => $screenshotInfo[0],
        ];
    }

    /**
     * Send screenshots to Diffy.
     *
     * @Then /^Send screenshots to diffy with name "([^"]*)"$/
     */
    public function sendScreenshots($screenshotName)
    {
        Diffy::setApiKey($this->apiKey);

        $this->createdScreenshotIds[] = Screenshot::createCustomScreenshot($this->projectId, $this->screenshots, $screenshotName);

        // Remove all screenshots from screenshotDir
        foreach (new DirectoryIterator($this->screenshotsDir) as $fileInfo) {
            if (!$fileInfo->isDot() && $fileInfo->isFile() && $fileInfo->getExtension() === 'png') {
                unlink($fileInfo->getPathname());
            }
        }
        $this->screenshots = [];
    }


    /**
     * Run diffy Diff.
     *
     * @Then /^Create diffy comparison$/
     */
    public function createDiff()
    {
        Diffy::setApiKey($this->apiKey);

        $screenshot1 = $this->createdScreenshotIds[0];
        $screenshot2 = $this->createdScreenshotIds[1];
        $this->createdDiffIds[] = Diff::create($this->projectId, $screenshot1, $screenshot2);
    }
}

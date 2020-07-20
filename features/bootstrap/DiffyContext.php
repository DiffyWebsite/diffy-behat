<?php

use Behat\Behat\Context\Context;
use Diffy\Diffy;
use Diffy\Diff;

/**
 * Defines application features from the specific context.
 */
class DiffyContext implements Context
{

    private $apiKey;

    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
    }

    /**
     * @Given I login with apiKey :apiKey
     */
    public function iLoginWithKey($apiKey) {
        Diffy::setApiKey($apiKey);
    }

    /**
     * @Given I get diff list for project ":projectId
     */
    public function iGetDiffListForProject($projectId) {
        $diffs = Diff::list($projectId);
        var_export($diffs);

//        $this->visit("/login");
//        $this->fillField("email", $username);
//        $this->fillField("password", $password);
//        $this->pressButton("Login");
    }


    /**
     * @Then I should see :arg1
     */
    public function iShouldSee($arg1)
    {
        throw new PendingException();
    }

}

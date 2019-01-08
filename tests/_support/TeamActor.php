<?php


/**
 * Inherited Methods
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = NULL)
 *
 * @SuppressWarnings(PHPMD)
*/
class TeamActor extends AcceptanceTester
{

    /**
     * TeamActor constructor.
     * @param \Codeception\Scenario $scenario an opaque object that codeception will automatically pass in
     */
    function __construct(Codeception\Scenario $scenario)
    {
        parent::__construct($scenario, "teamAttr.ini");
    }

    /**
     * Requests a clarification
     * @param string $clariText the text to put into the clarification
     * @param string $problemName the name of the problem which the clarification is for or "General".
     */
    public function requestClari(string $clariText, string $problemName = "General")
    {
        $I = $this;
        $I->amOnMyPage("clarifications.php");
        $I->click('Request Clarification');
        $I->selectOption("problem_id", $problemName);
        $I->fillField("question", $clariText);
        $I->click("submit");
    }

    /**
     * Submit a solution to the first problem
     * @param $path string a path to the file to submit where the _data directory is the root
     */
    public function submitSolution(string $path)
    {
        $I = $this;
        $I->amOnMyPage("submissions.php");
        $I->attachFile("source_file", $path);
        $I->click("Submit Solution");
    }

    /**
     * View the category page
     */
    public function viewCategory($catName)
    {
        $I = $this;
        $I->amOnMyPage("standings.php");
        $I->click($catName);
    }
}



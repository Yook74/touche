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
     * @param $problemNumber integer the problem number to submit a solution for
     */
    public function submitSolution(string $path, $problemNumber = 1)
    {
        $I = $this;
        $I->amOnMyPage("submissions.php");
        $I->selectOption("problem_id", $problemNumber);
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

//Standings Actions

    /**
     * Looks to see if $secondTeam is ahead of the team calling this function on the leaderboard
     * @return bool If True, $secondTeam is ahead of $myTeam
     */
    public function isAheadOfMe($secondTeam)
    {
        $pageSource = $this->grabPageSource();
        $myTeamPOS = strrpos($pageSource, $this->attr["name"]);
        $secondTeamPOS = strrpos($pageSource, $secondTeam);
        return $myTeamPOS > $secondTeamPOS;
    }

    /**
     * Gets the score for $problemNumber for $teamName
     * @return mixed actual problem score
     */
    public function getMyProblemScore($problemNumber, $teamName = null)
    {
        if($teamName == null)
            $teamName = $this->attr["name"];
        //$problemNumber is zero indexed
        $problemNumber = $problemNumber - 1;
        $score = $this->grabTextFrom("[name=\"$teamName Score $problemNumber\"]");
        $scoreArray = explode('/', $score);
        return $scoreArray[0];
    }

    /**
     * Gets the overall score for $teamName
     * @return mixed actual overall score
     */
    public function getMyOverallScore($teamName = null)
    {
        if($teamName == null)
            $teamName = $this->attr["name"];
        $score = $this->grabTextFrom("[name=\"$teamName Overall Score\"]");
        $scoreArray = explode('(', $score);
        return $scoreArray[0];
    }
}



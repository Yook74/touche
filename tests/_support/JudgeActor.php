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
class JudgeActor extends AcceptanceTester
{
    private $helper; // This object has access to all the acceptance helper methods

    /**
     * JudgeActor constructor.
     * @param \Codeception\Scenario $scenario an opaque object that codeception will automatically pass in
     * @param Helper\Acceptance $helper codeception will make this for us and we need it to connect to the DB
     * @throws \Codeception\Exception\ModuleException
     */
    public function __construct(\Codeception\Scenario $scenario, Helper\Acceptance $helper)
    {
        parent::__construct($scenario, "judgeAttr.ini");
        $helper->connectToDatabase(CreatorActor::getContestName());
        $this->helper = $helper;
    }

/* Clarification Actions */
    /**
     * Creates an unprompted clarification; that is, a clarification that is not in response to a question made by a team
     * @param $clariText string the text of the clarification
     */
    public function createUnpromptedClari(string $clariText)
    {
        $I = $this;
        $I->amOnMyPage("clarifications.php");
        $I->click("Make new Clarification");
        $I->fillField("response", $clariText);
        $I->click("submit");
    }

    /**
     * Responds to the first clarification it sees
     * @param string $responseText the text to respond to the clarification with
     */
    public function respondToClariRequest(string $responseText)
    {
        $I = $this;
        $I->amOnMyPage("clarifications.php");
        $I->click( 'Respond to Clarification');
        $I->fillField("response", $responseText);
        $I->click('submit');
    }

    /**
     * Deletes all the clarifications
     */
    public function cleanupClari()
    {
        $this->helper->executeSQL("TRUNCATE CLARIFICATION_REQUESTS;");
    }

/* Contest timing actions */
    /**
     * Starts the contest but does not start all the sites
     */
    public function startContest(){
        $I = $this;
        $I->amOnMyPage("start.php");
        $I->checkOption("input[value='contest']");
        $I->click("submit");
    }

    /**
     * @param $siteName string the name of the site to start
     */
    public function startSite($siteName){
        $I = $this;
        $I->amOnMyPage("start.php");
        $siteID = $I->grabFromDatabase('SITE', 'SITE_ID', array('SITE_NAME' => $siteName));
        $I->checkOption("input[value='$siteID']");
        $I->click("submit");
    }

    /**
     * Starts the contest and all of the sites
     */
    public function startAll(){
        $I = $this;
        $I->startContest();
        $siteNames = $I->grabColumnFromDatabase('SITE', 'SITE_NAME', array());
        foreach($siteNames as $name){
            $this->startSite($name);
        }
    }

/* Judging actions */
    /**
     * Judges the first problem on the judging page
     * $result is the response given to the team
     * $page is which page the submission should be reviewed on
     */
    public function judgeSubmission($result = "Error (Reason Unknown)", $page = "judge.php")
    {
        $I = $this;
        $I->amOnMyPage($page);
        $I->click("judge submission");
        $I->selectOption("result", $result);
        $I->click("Submit Results");
    }

    /**
     * Waits until any submission is ready for judging and then waits a little longer for the auto judgement to be made
     * @param int $expected_time how long the auto judging should take\
     * @param int $numTeams the total number of teams that submit solutions. This is used to wait for the last submission
     * to appear on the page
     * @param int $autoJudgeTime is the default amount of time to wait until timeout
     */
    public function waitForAutoJudging($expected_time = 7, $numTeams = 1, $autoJudgeTime = 65){
        $I = $this;
        $numTeams = $numTeams - 1;
        $I->amOnMyPage('judge.php');
        if($this->attr['invoke_cronscript']){
           $c_name = CreatorActor::getContestName();
           $result = system("php ../public_html/$c_name/judge/cronScript.php > /dev/null 2>&1");
           if($result != 0) throw new RuntimeException("Cronscript invocation failed");
        } else {
            $I->waitForElement("[name=\"submission$numTeams\"]", $autoJudgeTime);
        }
        $I->wait($expected_time);
        $I->reloadPage();
    }

    public function getEndHour()
    {
        $I = $this;
        $pageSource = $I->grabPageSource();
        $contestStringPos = strpos($pageSource, "Time Till Contest End");
        $contestString = substr($pageSource, $contestStringPos);
        $judgeEndHour = substr($contestString, 23, 2);
        return $judgeEndHour;
    }

/* Category Actions */

    /**
     * View the category page
     */
    public function viewCategory($catName)
    {
        $I = $this;
        $I->amOnMyPage("standings.php");
        $I->click($catName);
    }

/* Submission Actions */

    /**
     * Filter by selected $problemName
     */
    public function filterProblem($problemName)
    {
        $I = $this;
        $I->amOnMyPage("judge.php");
        $I->click($problemName);
    }

    /**
     * Filter by selected $teamName
     */
    public function filterByTeam($teamID)
    {
        $I = $this;
        $I->amOnMyPage("judge.php");
        $I->selectOption("team", $teamID);
    }

//Standings Actions

    /**
     * @return bool returns true if $team1 is ahead of $team2 on the leaderboard
     */
    public function isAheadOf($team1, $team2)
    {
        $pageSource = $this->grabPageSource();
        $team1POS = strrpos($pageSource, $team1);
        $team2POS = strrpos($pageSource, $team2);
        return $team1POS < $team2POS;
    }

    /**
     * Find and return the problem score for a given $teamName and $problemNumber
     */
    public function getAProblemScore($teamName, $problemNumber = "1")
    {
        $problemNumber = $problemNumber - 1; //Judge standings is 0 indexed
        $score = $this->grabTextFrom("[name=\"$teamName Score $problemNumber\"]");
        $scoreArray = explode('/', $score);
        return $scoreArray[0];
    }

    /**
     * Find and return the overall score for a given $teamName
     */
    public function getAnOverallScore($teamName)
    {
        $score = $this->grabTextFrom("[name=\"$teamName Overall Score\"]");
        $scoreArray = explode('(', $score);
        return $scoreArray[0];
    }

//Problem Actions

    public function getProblemNumber()
    {
        $I = $this;
        $problemName = parse_ini_file("adminAttr.ini")["problem_name"];
        return $I->grabFromDatabase('PROBLEMS', 'PROBLEM_ID',
            array('PROBLEM_NAME' => $problemName));
    }
}





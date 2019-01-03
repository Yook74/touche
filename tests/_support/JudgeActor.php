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

    /**
     * Rejects the first problem on the judging page with "Error (reason unknown)"
     * In future, this should probably be able to pick which submission to reject
     */
    public function rejectSubmission(){
        $I = $this;
        $I->amOnMyPage("judge.php");
        $I->click("judge submission");
        $I->selectOption("result", "Error (Reason Unknown)");
        $I->click("Submit Results");
    }

    /**
     * Waits until any submission is ready for judging and then waits a little longer for the auto judgement to be made
     * @param int $expected_time how long the auto judging should take
     */
    public function waitForAutoJudging($expected_time = 7){
        $I = $this;
        $I->amOnMyPage('judge.php');
        if($this->attr['invoke_cronscript']){
           $c_name = CreatorActor::getContestName();
           $result = system("php ../public_html/$c_name/judge/cronScript.php > /dev/null");
           if($result != 0) throw new RuntimeException("Cronscript invocation failed");
        } else {
            $I->waitForText('judge submission', 65); #wait for cron to call the cronscript
        }
        $I->wait($expected_time);
    }

    /**
     * Deletes all the clarifications
     */
    public function cleanupClari()
    {
        $this->helper->executeSQL("TRUNCATE CLARIFICATION_REQUESTS;");
    }
}

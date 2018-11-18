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
    }

    /**
     * Creates a general clarification; that is, a clarification that is not in response to a question made by a team
     * @param $clariText string the text of the clarification
     */
	public function createGeneralClari(string $clariText)
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
}

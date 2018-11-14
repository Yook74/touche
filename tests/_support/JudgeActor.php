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
class JudgeActor extends \Codeception\Actor
{
    // do not remove this line
    use _generated\AcceptanceTesterActions;

    public static $default_user= "Test-Judge";
    public static $default_pass= "judgePass7!";

    /**
     * AdminActor constructor.
     * Creating an JudgeActor object automatically logs you in.
     * This is kind of an odd choice, but it saves the class'es user from repeated calls to login
     * @param $scenario Codeception\Scenario is an opaque object that gets passed through
     */
    function __construct(Codeception\Scenario $scenario)
    {
        parent::__construct($scenario);
        $this->login(self::$default_user, self::$default_pass);
    }

    /**
     * Logs this judge in with the given credentials
     * This is automatically invoked in the constructor, but if you want to log in again you can call this
     */
    public function login($username, $password)
    {
		$I = $this;
		$I->amOnPage('/judge/index.php');
		$I->fillField('user', $username);
		$I->fillField('password', $password);
		$I->click('submit');
		$I->seeInCurrentUrl('main.php');
	}

    /**
     * Creates a general clarification; that is, a clarification that is not in response to a question made by a team
     * @param $clariText string the text of the clarification
     */
	public function createGeneralClari(string $clariText)
    {
        $I = $this;
        $I->amOnPage("/judge/clarifications.php");
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
        $I->amOnPage("/judge/clarifications.php");
        $I->click( 'Respond to Clarification');
        $I->fillField("response", $responseText);
        $I->click('submit');
    }

    /**
     * Starts the contest but does not start all the sites
     */
	public function startContest(){
		$I = $this;
        $I->amOnPage("/judge/start.php");
		$I->checkOption("input[value='contest']");
		$I->click("submit");
	}

    /**
     * @param $siteName string the name of the site to start
     */
	public function startSite($siteName){
        $I = $this;
        $I->amOnPage("/judge/start.php");
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

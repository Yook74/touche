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
class AcceptanceTester extends \Codeception\Actor
{
    // do not remove this line
	use _generated\AcceptanceTesterActions;

    /**
     * @param $username string username for the judge
     * @param $password string password for the judge
     */
	public function login ($username, $password)
	{
		$I = $this;
		$I->amOnPage('/judge/index.php');
		$I->fillField('user', $username);
		$I->fillField('password', $password);
		$I->click('submit');
		$I->seeInCurrentUrl('main.php');
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

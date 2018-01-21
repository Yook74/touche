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


	public function adminLogin($username, $password)
	{
		$I = $this;
        $I->amOnPage('/admin/index.php');
		$I->fillField('user',$username);
		$I->fillField('password', $password);
		$I->click('submit');
#		$I->seeCurrentUrlEquals('/admin/setup_contest.php');
	}
	
	public function judgeLogin ($username, $password)
	{
		$I = $this;
		$I->amOnPage('/judge/index.php');
		$I->fillField('user', $username);
		$I->fillField('password', $password);
		$I->click('submit');
#		$I->seeCurrentUrlEquals('/judge/main.php');
	}
	
	public function teamLogin($username, $password)
	{
		$I = $this;
		$I->amOnPage('/');
		$I->fillField('user', $username);
		$I->fillField('password', $password);
		$I->click('submit');
#		$I->seeCurrentUrlEquals('/main.php');
	}
	
	public function addTeam($teamName, $organization, $username, $password, $siteId, $contestant1, $contestant2, $contestant3, $alternate, $email, $coach)
	{
		$I = $this;
		$I->amOnPage("/admin/setup_teams.php");
		$I->fillField('team_name', $teamName);
        $I->fillField('organization', $organization);
        $I->fillField('username', $username);
        $I->fillField('password', $password);
        $I->selectOption('site_id', $siteId);
        $I->fillField('contestant_1_name', $contestant1);
        $I->fillField('contestant_2_name', $contestant2);
        $I->fillField('contestant_3_name', $contestant3);
        $I->fillField('alternate_name', $alternate);
        $I->fillField('email', $email);
        $I->fillField('coach_name', $coach);
        $I->click('submit');

	

	}

	public function addSite($siteName)
	{	
		$I = $this;
		$I->amOnPage('/admin/setup_site.php');
        $I->fillField('site_name', $siteName);
        $I->click('submit');
        $I->see($siteName);
		

	}

	public function startContest(){
	
		$I = $this;
		$I->judgeLogin("judge", "password");
        $I->amOnPage("/judge/start.php");
		$I->checkOption("input[value='contest']");
		$I->click("submit");
		$teamID = $I->grabFromDatabase('SITE', 'SITE_ID', array('SITE_NAME' => 'test-site'));
		$I->checkOption("input[value='$teamID']");		
		$I->click("submit");

		

	}





	
}

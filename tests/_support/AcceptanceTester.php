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

	public function indexLogin($name, $password)
	{
		$I = $this;
		$I->amOnPage('/index.php');
		$I->fillField('user', $name);
		$I->fillField('password', $password);
		$I->click('submit');
		$I->amOnPage('/createcontest.php');
	}
	
	public function createContest ($contestHost, $contestName, $dbPassword)
	{
		$I = $this;
		$I->fillField("contest_host", $contestHost);
		$I->fillField("contest_name", $contestName);
		$I->fillField("dbpassword", $dbPassword);
		$I->amOnPage('/createcontest2.php');
	}




}

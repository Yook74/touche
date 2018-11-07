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
     * @param $username string the username for the team
     * @param $password string the password for the team
     */
	public function login($username, $password)
	{
		$I = $this;
		$I->amOnPage('/');
		$I->fillField('user', $username);
		$I->fillField('password', $password);
		$I->click('submit');
		$I->seeCurrentUrlEquals('/main.php');
	}
}

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
class TeamActor extends \Codeception\Actor
{
    // do not remove this line
	use _generated\AcceptanceTesterActions;
	public $username = "Test-Team";
    public $password = "teamPass7!";

    /**
     * Logs the team in using the username and password declared above.
     * If you want to use a different username and password, change those values.
     */
	public function login()
	{
		$I = $this;
		$I->amOnPage('/');
		$I->fillField('user', $this->username);
		$I->fillField('password', $this->password);
		$I->click('submit');
		$I->seeCurrentUrlEquals('/main.php');
	}

    /**
     * @param $clariText string the text we are looking for in a clarification
     */
    public function lookForClariText(string $clariText)
    {
        $I = $this;
        $I->amOnPage("/clarifications.php");
        $I->see($clariText);
    }
}

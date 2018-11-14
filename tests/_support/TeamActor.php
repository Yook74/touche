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

    public static $default_user= "Test-Team";
    public static $default_pass= "teamPass7!";

    /**
     * AdminActor constructor.
     * Creating an TeamActor object automatically logs you in.
     * This is kind of an odd choice, but it saves the class'es user from repeated calls to login
     * @param $scenario Codeception\Scenario is an opaque object that gets passed through
     */
    function __construct(Codeception\Scenario $scenario)
    {
        parent::__construct($scenario);
        $this->login(self::$default_user, self::$default_pass);
    }

    /**
     * Logs this team in with the given credentials
     * This is automatically invoked in the constructor, but if you want to log in again you can call this
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

    /**
     * @param string $clariText string the text to put into the clarification
     */
    public function requestClari(string $clariText)
    {
        $I = $this;
        $I->amOnPage("/clarifications.php");
        $I->click('Request Clarification');
        $I->fillField("question", $clariText);
        $I->click("submit");
    }
}

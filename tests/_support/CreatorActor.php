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
class CreatorActor extends \Codeception\Actor
{
    // do not remove this line
	use _generated\AcceptanceTesterActions;
    public static $default_user= "create";
    public static $default_pass= "contest";

    /**
     * CreatorActor constructor.
     * Creating an CreatorActor object automatically logs you in.
     * This is kind of an odd choice, but it saves the class'es user from repeated calls to login
     * @param $scenario Codeception\Scenario is an opaque object that gets passed through
     */
    function __construct(Codeception\Scenario $scenario)
    {
        parent::__construct($scenario);
        $this->login(self::$default_user, self::$default_pass);
    }

    /**
     * Logs this actor in with the given credentials
     * This is automatically invoked in the constructor, but if you want to log in again you can call this
     */
	public function login($username, $password)
	{
		$I = $this;
        $I->amOnPage('/index.php');
		$I->fillField('user', $username);
		$I->fillField('password', $password);
		$I->click('submit');
	}
}

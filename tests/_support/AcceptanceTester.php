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
    use _generated\AcceptanceTesterActions;
    public $attr;

    /**
     * Creating an AcceptanceTester object automatically logs you in.
     * This is kind of an odd choice, but it saves us from repeated calls to login
     * @param $scenario Codeception\Scenario is an opaque object that gets passed through
     * @param $iniPath string path to a .ini file that contains information like the username of the actor.
     * This parameter is pretty important to making the whole thing work properly but it is not strictly necessary
     * Leave it as null if you don't want to log in as anybody
     */
    public function __construct(Codeception\Scenario $scenario, $iniPath = null)
    {
        parent::__construct($scenario);
        if ($iniPath != null) {
            $this->attr = parse_ini_file($iniPath);
            $this->attrLogin();
        }
        # Otherwise be dumb
    }

    /**
     * Logs this actor in with the given credentials
     * This is automatically invoked in the constructor, but if you want to log in again you can call this
     */
    public function login($username, $password)
    {
        $I = $this;
        $I->amOnMyPage("");
        $I->fillField('user', $username);
        $I->fillField('password', $password);
        $I->click('submit');
    }

    /**
     * Wrapper for login() that uses the username and password in attr.
     * All actors are not guaranteed to have a username and password so this may not always work
     */
    public function attrLogin()
    {
        $this->login($this->attr['username'], $this->attr['password']);
    }

    /**
     * Logs in incorrectly; should get rejected
     */
    public function incorrectLogin()
    {
        $this->login($this->attr['username'], $this->attr['password'] . "behat");
    }

    /**
     * Similar to the amOnPage method, but this automatically adds the contest's name and the actor's base page
     * For example, calling amOnMyPage("") as an admin puts you on the /<contest name>/admin/ page
     * @param $page
     */
    public function amOnMyPage($page)
    {
        $contestName = CreatorActor::getContestName();
        $this->amOnPage($contestName . $this->attr['base_page'] . "/" .$page);
    }

    /**
     * Similar to the fillField method, but this automatically accesses the attr associative array
     * @param $field string which field to fill
     * @param $attrName string the name of the attr to fill it with
     */
    public function fillFieldWithAttr($field, $attrName)
    {
        $this->fillField($field, $this->attr[$attrName]);
    }
}

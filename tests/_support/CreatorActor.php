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
class CreatorActor extends AcceptanceTester
{
    /**
     * CreatorActor constructor.
     * @param \Codeception\Scenario $scenario an opaque object that codeception will automatically pass in
     */
    function __construct(Codeception\Scenario $scenario)
    {
        parent::__construct($scenario, "creatorAttr.ini");
    }

    /**
     * Special login function for Creators
     * Uses amOnPage instead of amOnMyPage
     * This is automatically invoked in the constructor, but if you want to log in again you can call this
     */
    public function login($username, $password)
    {
        $I = $this;
        $I->amOnPage(""); #This is the only difference
        $I->fillField('user', $username);
        $I->fillField('password', $password);
        $I->click('submit');
    }
    /**
     * Creates a contest and waits for the creation process to finish
     */
    public function createContest()
    {
        $I = $this;
        $I->fillFieldWithAttr("contest_host", "contest_host");
        $I->fillFieldWithAttr("contest_name", "contest_name");
        $I->assertNotEquals($this->attr["db_root_password"], "",
            "You must fill out the db_root_password in tests/_support/creatorAttr.ini");

        $I->fillFieldWithAttr("dbpassword", "db_root_password");
        $I->click("B1");
        # It may be wise to put a waitforelement here, but the click() automatically does that
    }

    public function deleteContest(\Helper\Acceptance $helper)
    {
        $name = self::getContestName();
        system("rm -rf ~/public_html/$name");
        system("sudo rm -rf ~/$name");
        system("sudo rm -rf ~/active-contests/$name");
        $helper->executeSQL("DROP DATABASE $name");
    }

    /**
     * A static method to get the contest name.
     * @return string the name of the contest
     */
    public static function getContestName(){
        return parse_ini_file("creatorAttr.ini")['contest_name'];
    }

    /**
     * Points all the actors to the given $contestName
     * Make sure that if you invoke this you keep the old contest name
     */
    public static function switchContest($path, $contestName, $newContestName, $I)
    {
        //overwrite creatorAttr.ini with new name
        $file_string = file_get_contents($path);
        $new_string = str_replace($contestName, $newContestName, $file_string, $count);
        if($count != 1)
            die("Multiple occurrences of $contestName in .ini file");

        $handle = fopen($path, "w") OR die("Error writing file $path");
        fwrite($handle, $new_string);
        fclose($handle);
    }

    /**
     * Sets the admin's credentials to the ones defined in adminAttr.ini
     */
    public function setAdminCredentials()
    {
        $I=$this;
        $adminAttr = parse_ini_file("tests/_support/adminAttr.ini");
        $I->amOnMyPage("/admin");

        $I->fillField("user", "admin");
        $I->fillField("password", "admin");
        $I->click("submit");

        $I->fillField("user", $adminAttr["username"]);
        $I->fillField("password", $adminAttr["password"]);
        $I->fillField("password2", $adminAttr["password"]);
        $I->click("submit");
    }
}

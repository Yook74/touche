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
class AdminActor extends AcceptanceTester
{
    private $helper; // This object has access to all the acceptance helper methods

    /**
     * AdminActor constructor.
     * @param \Codeception\Scenario $scenario an opaque object that codeception will automatically pass in
     * @param Helper\Acceptance $helper codeception will make this for us and we need it to connect to the DB
     * @throws \Codeception\Exception\ModuleException
     */
    public function __construct(\Codeception\Scenario $scenario, Helper\Acceptance $helper)
    {
        parent::__construct($scenario, "adminAttr.ini");
        $helper->connectToDatabase(CreatorActor::getContestName());
        $this->helper = $helper;
    }

    /**
     * Adds the team described in teamAttr.ini
     * Some of these fields are hardcoded because I don't think they're used for anything
     */
	public function addDefaultTeam()
	{
		$I = $this;
        $teamAttr = parse_ini_file("teamAttr.ini");

		$I->amOnMyPage("setup_teams.php");
		$I->fillField('team_name', $teamAttr['name']);
        $I->fillField('organization', 'Organization');
        $I->fillField('username', $teamAttr['username']);
        $I->fillField('password', $teamAttr['password']);
        # Could fill site dropdown here
        $I->fillField('contestant_1_name', "Contestant One");
        $I->fillField('contestant_2_name', "Contestant Two");
        $I->fillField('contestant_3_name', "Contestant Three");
        $I->fillField('alternate_name', "Alternate");
        $I->fillField('email', "email@example.com");
        $I->fillField('coach_name', "Dr. Coach");
        $I->click('submit');
	}

    /**
     * Adds a team with the given parameters
     * Some of the fields are left blank and others are hardcoded
     */
	public function addSimpleTeam($name, $username, $password)
	{
		$I = $this;
		$I->amOnMyPage("setup_teams.php");
		$I->fillField('team_name', $name);
        $I->fillField('username', $username);
        $I->fillField('password', $password);
        $I->click('submit');
	}

    /**
     * Deletes the first team it sees because it is stupid
     */
	public function deleteTeam()
    {
        $I = $this;
        $I->amOnMyPage("setup_teams.php");
        $I->click("Delete");
    }

    /**
     * Create a site described in adminAtr.ini
     */
	public function addSite()
	{	
		$I = $this;
		$I->amOnMyPage('setup_site.php');
        $I->fillFieldWithAttr('site_name','site_name');
        $I->click('submit');
	}

    /**
     * Set the judge's credentials to those described in judgeAttr.ini
     */
	public function setJudgeCredentials()
    {
        $I = $this;
        $judgeAttr = parse_ini_file('judgeAttr.ini');

        $I->amOnMyPage("setup_contest.php");
        $I->fillField("username", $judgeAttr['username']);
        $I->fillField("password", $judgeAttr['password']);
        $I->click("B1");
    }

    /**
     * Create the problem described in adminAttr.ini
     */
    public function createProblem()
    {
        $I=$this;
        $I->amOnMyPage("setup_problems.php");
        $I->fillFieldWithAttr("problem_name", "problem_name");
        $I->fillFieldWithAttr("problem_loc", "problem_location");
        $I->click("Submit");
    }

    /**
     * Adds two datasets to the default problem
     */
    public function addDatasets()
    {
        $I = $this;
        $I->amOnMyPage("setup_data_sets.php");
        $I->click("Add new data set"); //If more than one problem exists this may cause issues
        $I->attachFile("data_set_in", $this->attr["data_in_path1"]);
        $I->attachFile("data_set_out", $this->attr["data_out_path1"]);
        $I->click("Submit");
        $I->attachFile("data_set_in", $this->attr["data_in_path2"]);
        $I->attachFile("data_set_out", $this->attr["data_out_path2"]);
        $I->click("Submit");
    }

    /**
     * Change the default team's password
     */
    public function changeTeamPassword($newPassword)
    {
        $I = $this;
        $I->click("Teams");
        $I->click("Edit");
        $I->fillField("password",$newPassword);
        $I->click("Submit");
    }

    /**
     * Set the default team's password back
     */
    public function resetTeamPassword()
    {
        $I = $this;
        $teamAttr = parse_ini_file('teamAttr.ini');

        $I->click("Teams");
        $I->click("Edit");
        $I->fillField("password",$teamAttr['password']);
        $I->click("Submit");
    }

    /**
     * Change the default judge's password
     */
    public function changeJudgePassword($newPassword)
    {
        $I = $this;
        $I->click("Edit contest details");
        $I->fillField("password",$newPassword);
        $I->click("Submit");
    }

    /**
     * Set the default judge's password back
     */
    public function resetJudgePassword()
    {
        $I = $this;
        $judgeAttr = parse_ini_file('judgeAttr.ini');

        $I->click("Edit contest details");
        $I->fillField("password",$judgeAttr['password']);
        $I->click("Submit");
    }

    /**
     * Log out as Admin and then incorrectly log back in
     */
    public function incorrectLogin()
    {
        $I = $this;
        $I->amOnMyPage("");
        $I->login($I->attr["username"],$I->attr["password"]."behat");
    }
}

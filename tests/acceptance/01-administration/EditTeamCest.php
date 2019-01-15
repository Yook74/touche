<?php
use Codeception\Util\Locator;

class EditTeamCest
{
    public static $newTeamName = "John Luscombe";
    public static $username = "john";
    public static $password = "johnPass7!";

    public function addNewTeam(AdminActor $I)
    {
        $I->wantTo("Add a new team to the contest");
        $I->addSimpleTeam(self::$newTeamName, self::$username, self::$organization, self::$password);
        $originalTeamName = parse_ini_file('tests/_support/teamAttr.ini')["name"];
        $I->see($originalTeamName);
        $I->see(self::$newTeamName);
    }

    public function newLogin(TeamActor $I)
    {
        $I->wantTo("Login with the new team's credentials");
        $I->login(self::$username, self::$password);
        $I->seeInCurrentUrl("main");
    }

    public function cancelEdit(AdminActor $I)
    {
        $I->wantTo("Edit a team, cancel editing, and then add a new team");
        $I->teamEditCancel();
        $originalTeamName = parse_ini_file('tests/_support/teamAttr.ini')["name"];
        $I->see($originalTeamName);
    }

    public function deleteTeam(AdminActor $I){
        $I->wantTo("Delete a team");
        $I->deleteTeam();
        $I->see("Team deleted successfully");
    }

    public function deletedLogin(TeamActor $I)
    {
        $I->wantTo("Get rejected when the team enters the deleted credentials");
        $I->attrLogin();
        $I->dontSeeInCurrentUrl("main");
    }

    public function duplicateTeams(AdminActor $I)
    {
        $I->wantTo("Create duplicate teams");
        $I->addSimpleTeam(self::$newTeamName, self::$username,  self::$password);
        $I->addSimpleTeam(self::$newTeamName, self::$username,  self::$password);
        $I->see(self::$newTeamName);
        $I->deleteTeam();
        $I->see(self::$newTeamName);
        $I->addSimpleTeam(self::$newTeamName, self::$username, self::$password);
    }

    public function deleteDuplicateTeams(AdminActor $I)
    {
        $I->wantTo("Delete duplicate teams");
        $I->deleteTeam();
        $I->see("Team deleted successfully");
        $I->deleteTeam();
        $I->see("Team deleted successfully");
    }

    public function editTeam(AdminActor $I)
    {
        $I->wantTo("Edit a team's details");
        $I->editTeam();
        $I->see("Team changed successfully");
    }

    public function editedTeamLogin(TeamActor $I)
    {
        $I->wantTo("Login after editing a team's credentials");
        $I->seeInCurrentUrl("main");
    }
}
<?php
use Codeception\Util\Locator;

class LoginCest
{
    public static $changedPassword = "changedPassword123";

    public function correctAdminLogin(AdminActor $I)
    {
        $I->wantTo("Try to log in as Admin");
        $I->seeInCurrentUrl("setup_contest"); # Logged in with the constructor
    }

    public function correctJudgeLogin(JudgeActor $I)
    {
        $I->wantTo('Try to log in as Judge');
        $I->seeInCurrentUrl("main.php"); # Logged in with the constructor
    }

    public function correctTeamLogin(TeamActor $I)
    {
        $I->wantTo('Try to log in as Team');
        $I->seeInCurrentUrl("main.php"); # Logged in with the constructor
    }

    public function incorrectAdminLogin(AdminActor $I)
    {
        $I->wantTo("Get rejected when the Admin enters the wrong credentials");
        $I->incorrectLogin();
        $I->dontSee("Edit Contest Info");
    }

    public function incorrectJudgeLogin(JudgeActor $I)
    {
        $I->wantTo("Get rejected when the Judge enters the wrong credentials");
        $I->incorrectLogin();
        $I->dontSee("Edit Contest Info");
    }

    public function incorrectTeamLogin(TeamActor $I)
    {
        $I->wantTo("Get rejected when the Team enters the wrong credentials");
        $I->incorrectLogin();
        $I->dontSee("Welcome");
    }

    /**
     * @depends correctTeamLogin
     * @depends correctAdminLogin
     */
    public function changeTeamPassword(AdminActor $I)
    {
        $I->wantTo("Change the team's password");
        $I->changeTeamPassword(self::$changedPassword);
        $I->see("Team changed successfully");
    }

    /**
     * @depends correctJudgeLogin
     * @depends correctAdminLogin
     */
    public function changeJudgePassword(AdminActor $I)
    {
        $I->wantTo("Change the judge's password");
        $I->changeJudgePassword(self::$changedPassword);
    }

    /**
     * @depends changeTeamPassword
     */
    public function retryTeamLogin(TeamActor $I)
    {
        $I->wantTo("Log in with the team's username and new password");
        $I->dontSeeInCurrentUrl("main");
        $I->login($I->attr["username"], self::$changedPassword);
        $I->seeInCurrentUrl("main");
    }

    /**
     * @depends changeJudgePassword
     */
    public function retryJudgeLogin(JudgeActor $I)
    {
        $I->wantTo("Log in with the judge's new username and password");
        $I->dontSeeInCurrentUrl("main");
        $I->login($I->attr["username"], self::$changedPassword);
        $I->see("Please select an option.");
    }

    /**
     * @depends retryTeamLogin
     */
    public function resetTeamPassword(AdminActor $I)
    {

        $I->wantTo("Reset the team's password to default");
        $I->resetTeamPassword();
        $I->see("Team changed successfully");
    }

    /**
     * @depends retryJudgeLogin
     */
    public function resetJudgePassword(AdminActor $I)
    {
        $I->wantTo("Reset the judge's password to default");
        $I->resetJudgePassword();
    }
}

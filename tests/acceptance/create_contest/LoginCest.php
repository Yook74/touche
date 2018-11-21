<?php
use Codeception\Util\Locator;

class LoginCest
{
    public function correctAdminLogin(AdminActor $I)
    {
        $I->wantTo("Try to log in as admin");
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
        $I->wantTo("Get rejected when the Admin enters the wrong credentials [STUB]");
    }

    public function incorrectJudgeLogin(JudgeActor $I)
    {
        $I->wantTo("Get rejected when the Judge enters the wrong credentials [STUB]");
    }

    public function incorrectTeamLogin(TeamActor $I)
    {
        $I->wantTo("Get rejected when the Team enters the wrong credentials [STUB]");
    }

    /**
     * @depends correctTeamLogin
     */
    public function changeTeamPassword(AdminActor $I)
    {
        $I->wantTo("Change the team's password [STUB]");
    }

    /**
     * @depends correctJudgeLogin
     */
    public function changeJudgePassword(AdminActor $I)
    {
        $I->wantTo("Change the judge's password [STUB]");
    }

    /**
     * @depends changeTeamPassword
     */
    public function retryTeamLogin(TeamActor $I)
    {
        $I->wantTo("Log in with the team's new username and password [STUB]");
    }

    /**
     * @depends changeJudgePassword
     */
    public function retryJudgeLogin(JudgeActor $I)
    {
        $I->wantTo("Log in with the judge's new username and password [STUB]");
    }

    /**
     * @depends retryTeamLogin
     */
    public function resetTeamPassword(AdminActor $I)
    {
        $I->wantTo("Reset the team's password to default [STUB]");
    }

    /**
     * @depends retryJudgeLogin
     */
    public function resetJudgePassword(AdminActor $I)
    {
        $I->wantTo("Reset the judge's password to default[STUB]");
    }
}

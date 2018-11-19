<?php


class CreateCest
{
    public function createContest(CreatorActor $I)
    {
        $I->wantTo("Create a new contest");
        $I->createContest();
        $I->dontSee("gave exit code");
        $I->dontSee("failed");
        $I->dontSee("Error");
        $I->click("Administration setup");
        $I->see("Login");
    }

    /**
     * @depends createContest
     */
    public function setAdminPassword(CreatorActor $I)
    {
        $I->wantTo("Set the admin password");
        $I->setAdminCredentials();
        $I->seeInCurrentUrl("setup_contest");
    }

    /**
     * @depends setAdminPassword
     */
    public function testAdminLogin(AdminActor $I)
    {
        $I->wantTo("Try to log in as admin");
        $I->seeInCurrentUrl("setup_contest"); # Logged in with the constructor
    }

    /**
     * @depends testAdminLogin
     */
    public function setJudgeCredentials(AdminActor $I)
    {
        $I->wantTo('Change Judge Credentials');
        $I->setJudgeCredentials();
    }

    /**
     * @depends setJudgeCredentials
     */
    public function testJudgeLogin(JudgeActor $I)
    {
        $I->wantTo('Try to log in as Judge');
        $I->seeInCurrentUrl("main.php"); # Logged in with the constructor
    }

    /**
     * @depends testAdminLogin
     */
    public function createSite(AdminActor $I)
    {
        $I->wantTo("Create a site");
        $I->addSite();
        $I->see($I->attr["site_name"]);
    }

    /**
     * @depends createSite
     */
    public function createTeam(AdminActor $I)
    {
        $I->wantTo("Create a team");
        $I->addTeam();
    }

    /**
     * @depends createTeam
     */
    public function testTeamLogin(TeamActor $I)
    {
        $I->wantTo('Try to log in as Team');
        $I->seeInCurrentUrl("main.php"); # Logged in with the constructor
    }
}

<?php


class CreateContestCest
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
    public function setJudgeCredentials(AdminActor $I)
    {
        $I->wantTo('Change Judge Credentials');
        $I->setJudgeCredentials();
    }

    /**
     * @depends setAdminPassword
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
}

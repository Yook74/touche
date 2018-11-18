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

        $this->setAdminPassword($I);
    }

    /**
     * This is a helper and not a test because I need the state that I have at the end of createContest
     * @param CreatorActor $I needs to be actually passed in because I can't have it constructed twice
     */
    private function setAdminPassword(CreatorActor $I)
    {
        $adminAttr = parse_ini_file("tests/_support/adminAttr.ini");
        $I->fillField("user", "admin");
        $I->fillField("password", "admin");
        $I->click("submit");

        $I->fillField("user", $adminAttr["username"]);
        $I->fillField("password", $adminAttr["password"]);
        $I->fillField("password2", $adminAttr["password"]);
        $I->click("submit");
        $I->seeInCurrentUrl("setup_contest");
    }
}

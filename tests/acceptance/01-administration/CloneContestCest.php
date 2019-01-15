<?php

class CloneContestCest
{
    private $originalContestName;
    private static $newContestName = "clone_contest";
    private static $path = 'tests/_support/creatorAttr.ini';
    public static $newCat = "Hurricanes";

    public function editOriginalContest(AdminActor $I)
    {
        $I->wantTo("Add a category for testing");
        $I->createCategory(self::$newCat);
    }

    public function cloneContest(AdminActor $I)
    {
        $this->originalContestName = CreatorActor::getContestName();
        $I->wantTo("Clone $this->originalContestName into a new contest");
        $I->cloneContest(self::$newContestName);
        $I->waitForText(self::$newContestName, 120);
        $I->dontSee("gave exit code");
        $I->dontSee("failed");
        $I->dontSee("Error");
        CreatorActor::switchContest(self::$path, $this->originalContestName, self::$newContestName, $I);
    }

    public function confirmClonedContest(AdminActor $I)
    {
        $I->wantTo("Check to see if things have been cloned correctly");
        $I->amOnMyPage("setup_teams.php");
        $I->see(parse_ini_file('tests/_support/teamAttr.ini')["name"]);
        $I->amOnMyPage("setup_categories.php");
        $I->see(self::$newCat);
    }

    public function correctTeamLogin(TeamActor $I)
    {
        $I->wantTo('Try to log in as Team');
        $I->seeInCurrentUrl("main.php"); // Logged in with the constructor
    }

    public function deleteContest(CreatorActor $I, \Helper\Acceptance $helper)
    {
        $I->wantTo("Delete the cloned contest");
        $I->deleteContest($helper);
        CreatorActor::switchContest(self::$path, self::$newContestName, $this->originalContestName, $I);
    }

    public function cleanup(AdminActor $I)
    {
        $I->wantTo("Clean up any information to reset to default");
        $I->deleteCategory();
    }
}
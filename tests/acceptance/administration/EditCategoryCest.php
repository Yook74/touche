<?php

class EditCategoryCest
{
    public static $firstCatName = "Taylor";
    public static $secondCatName = "Wheaton";
    public static $otherCatName = "Hope";

    public static $teamName = "Battle Bus Fiends";
    public static $teamUser = "wolverineeeee";
    public static $teamPass = "battlePass7!";
    public function createCategory(AdminActor $I)
    {
        $I->wantTo("Create a category");
        $I->createCategory(self::$firstCatName);
        $I->see("Successful: New category created");
    }

    public function deleteCategory(AdminActor $I)
    {
        $I->wantTo("Delete a category");
        $I->deleteCategory();
        $I->see("Category deleted successfully");
    }

    public function createDifferentCategory(AdminActor $I)
    {
        $I->wantTo("Create another category");
        $I->createCategory(self::$otherCatName);
    }

    public function editCategory(AdminActor $I)
    {
        $I->wantTo("Edit an existing category");
        $I->editCategory(self::$secondCatName);
        $I->see("Category changed successfully");
    }

    public function checkTeamCategoryBox(AdminActor $I)
    {
        $I->wantTo("Click a check box in Team Category");
        $checkBox = $I->checkTeamCategoryBox(self::$secondCatName);
        $I->seeCheckboxIsChecked($checkBox);
    }

    public function viewNewCategoryJudge(JudgeActor $I)
    {
        $I->wantTo("View the newly created category as a Judge");
        $I->amOnMyPage("standings.php");
        $I->see(self::$secondCatName);
    }

    public function viewNewCategoryTeam(TeamActor $I)
    {
        $I->wantTo("View the newly created category as a Team");
        $I->amOnMyPage("standings.php");
        $I->see(self::$secondCatName);
    }

    public function addTeam(AdminActor $I)
    {
        $I->wantTo("Add a team to test the functionality of categories");
        $I->addSimpleTeam(self::$teamName, self::$teamUser, self::$teamPass);
    }

    public function viewTeamsInCategoryAsTeam(TeamActor $I)
    {
        $I->wantTo("View the newly created category with only the new team in it as a Team");
        $I->viewCategory(self::$secondCatName);
        $I->seeInCurrentUrl(self::$secondCatName);
        $I->dontSee(self::$teamName);
        $I->click("Overall");
        $I->dontSeeInCurrentUrl(self::$secondCatName);
        $I->see(self::$teamName);
    }

    public function viewTeamsInCategoryAsJudge(JudgeActor $I)
    {
        $I->wantTo("View the newly created category with only the new team in it as a Judge");
        $I->viewCategory(self::$secondCatName);
        $I->seeInCurrentUrl(self::$secondCatName);
        $I->dontSee(self::$teamName);
        $I->click("Overall");
        $I->dontSeeInCurrentUrl(self::$secondCatName);
        $I->see(self::$teamName);
    }

    public function uncheckTeamCategoryBox(AdminActor $I)
    {
        $I->wantTo("Uncheck a check box in Team Category");
        $checkBox = $I->uncheckTeamCategoryBox(self::$secondCatName);
        $I->dontSeeCheckboxIsChecked($checkBox);
    }

    public function viewNoCategoryJudge(JudgeActor $I)
    {
        $I->wantTo("Make sure the category is gone as a Judge");
        $I->amOnMyPage("standings.php");
        $I->dontSee(self::$secondCatName);
    }

    public function viewNoCategoryTeam(TeamActor $I)
    {
        $I->wantTo("Make sure the category is gone as a Team");
        $I->amOnMyPage("standings.php");
        $I->dontSee(self::$secondCatName);
    }

    public function editCancelCategory(AdminActor $I)
    {
        $I->wantTo("Cancel editing and then create a new category");
        $I->categoryEditCancel();
        $I->createCategory(self::$firstCatName);
        $I->see(self::$secondCatName);
    }

    public function cleanup(AdminActor $I)
    {
        $I->wantTo("Clean up my test code");
        $I->deleteCategory();
        $I->deleteTeam();
        $I->deleteTeam();
        $I->addDefaultTeam();
    }
}
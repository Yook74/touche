<?php


class AdminAddCategoryCest
{
    public function addCategory(AcceptanceTester $I)
    {
		$I->wantTo("Add team to new category");
		$I->adminLogin("admin", "password");
		$I->amOnPage("/admin/setup_categories");
		$I->fillField("category_name", "test-category");
		$I->click('submit');
		$I->amOnPage("/admin/setup_team_category");
		$categoryNum = $I->grabNumRecords('CATEGORIES');
		$teamID = $I->grabFromDatabase('TEAMS', 'TEAM_ID', array('TEAM_NAME' => 'Test-Team'));
		$I->checkOption("$teamID|$categoryNum");
		$I->click("submit");
		$I->judgeLogin("judge", "password");
		$I->amOnPage("/judge/standings.php");
		$I->see("test-category");
    }
}

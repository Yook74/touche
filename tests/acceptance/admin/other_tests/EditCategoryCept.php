<?php
use Codeception\Util\Locator; 
$I = new AcceptanceTester($scenario);
$I->wantTo('Edit a category');
$I->adminLogin('admin', 'password');
$I->amOnPage('/admin/setup_categories');
$categoryID = $I->grabFromDatabase('CATEGORIES', 'CATEGORY_ID', array('CATEGORY_NAME' => 'test-category'));
$I->click(Locator::href("setup_categories.php?edit_id=$categoryID"));
$I->fillField("category_name", "changed-category");
$I->click('submit');
$I->amOnPage("/admin/setup_team_category");
$categoryNum = $I->grabNumRecords('CATEGORIES');
$teamID = $I->grabFromDatabase('TEAMS', 'TEAM_ID', array('TEAM_NAME' => 'Test-Team'));
$I->checkOption("$teamID|$categoryNum");
$I->click("submit");
$I->judgeLogin("judge", "password");
$I->amOnPage("/judge/standings.php");
$I->see("changed-category");



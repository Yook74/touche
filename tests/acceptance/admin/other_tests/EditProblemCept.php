<?php
use Codeception\Util\Locator; 
$I = new AcceptanceTester($scenario);
$I->wantTo('Edit a problem');
$I->adminLogin("admin", "password");
$I->amOnPage("/admin/setup_problems.php");
$categoryID = $I->grabFromDatabase('PROBLEMS', 'PROBLEM_ID', array('PROBLEM_NAME' => 'test-problem'));
$I->click( Locator::href("setup_problems.php?problem_id=$categoryID" ));
$I->fillField("problem_name", "change-problem");
$I->fillField("problem_loc", "hardest");
$I->fillField("problem_note", "This is just an edit");
$I->click('submit');
$I->teamLogin("Test-Team", "test");
$I->amOnPage("/problems.php");
$I->see("change-problem");
$I->judgeLogin("judge", "password");
$I->amOnPage("/judge/problems.php");
$I->see("change-problem");

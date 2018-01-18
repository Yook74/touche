<?php
use Codeception\Util\Locator; 
$I = new AcceptanceTester($scenario);
$I->wantTo('Edit a team');
$I->adminLogin('admin', 'password');
$I->amOnPage("/admin/setup_teams.php");
$teamID = $I->grabFromDatabase('TEAMS', 'TEAM_ID', array('TEAM_NAME' => 'Test-Team'));
$I->click( Locator::href("setup_teams.php?team_id=$teamID" ));
$I->fillField('team_name','change-team');
$I->click('submit');
$I->amOnPage('/admin/setup_team_category.php');
$I->see('change-team');
$I->updateInDatabase('TEAMS', array('TEAM_NAME' => 'Test-Team'), array('TEAM_NAME' => 'Test-Team'));


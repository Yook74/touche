<?php
use Codeception\Util\Locator;

class CleanupCest
{
   
    public function tryToTest(AcceptanceTester $I)
    {
		
		$problemID = $I->grabFromDatabase('PROBLEMS', 'PROBLEM_ID', array('PROBLEM_NAME' => 'change-problem'));
		$siteID = $I->grabFromDatabase('SITE', 'SITE_ID', array('SITE_NAME' => 'change-site'));
		$teamID = $I->grabFromDatabase('TEAMS', 'TEAM_ID', array('TEAM_NAME' => 'change-team'));
		$categoryID = $I->grabFromDatabase('CATEGORIES', 'CATEGORY_ID', array('CATEGORY_NAME' => 'changed-category'));
		
		$I->wantTo("Cleanup Contest");
		
		//Admin Cleanup
		$I->adminLogin("admin", "password");
        $I->amOnPage("/admin/setup_categories");
		$I->click( Locator::href("setup_categories.php?remove_id=$categoryID" ));
		$I->amOnPage("/admin/setup_teams");
        $I->click( Locator::href("setup_teams.php?remove_id=$teamID" )); 
		$I->amOnPage("/admin/setup_site");
        $I->click( Locator::href("setup_site.php?remove_id=$siteID" ));
		$I->amOnPage("/admin/setup_problems");
        $I->click( Locator::href("setup_problems.php?remove_id=$problemID" ));  
		$I->amOnPage('/admin/misc.php');
		$I->click('B2');				
        



		//Judge Cleanup



		// Team Cleanup


    }
}

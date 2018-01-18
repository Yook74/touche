<?php


class AdminAddProblemCest
{
    public function tryToTest(AcceptanceTester $I)
    {
	
		$I->wantTo("Make sure the admin can add problems and teams can view them");
		$I->adminLogin("admin", "password");
		$I->amOnPage("/admin/setup_problems.php");
		$I->fillField("problem_name", "test-problem");
		$I->fillField("problem_loc", "hard");
		$I->fillField("problem_note", "This is just a test");
		$I->click('submit');
		$I->teamLogin("Test-Team", "test");
		$I->amOnPage("/problems.php");
		$I->see("test-problem");
		$I->judgeLogin("judge", "password");
		$I->amOnPage("/judge/problems.php");
        $I->see("test-problem");	
	
    }
}

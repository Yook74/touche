<?php


class AdminFrozenStandingsCest
{
    public function editFrozenStandings(AcceptanceTester $I)
    {

		$I->wantTo("Edit Frozen Standings Duration and ensure the change effects other users");
		$I->adminLogin("admin","password");
		$I->amOnPage("/admin/setup_contest.php");
		$I->fillField("freeze_minute", "10");
		$I->click("B1");
		$I->judgeLogin("judge", "password");
		$I->amOnPage("/judge/main.php");
		$I->see("Time left until freeze begins:");

    }
}

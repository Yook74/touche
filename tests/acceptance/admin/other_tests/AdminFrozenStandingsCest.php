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
		$I->teamLogin("Test-Team", "test");
		$I->amOnPage("/namey/main.php");
		$freezeTime = substr($I->grabTextFrom("/html/body/table/tbody/tr/td/table/tbody/tr[2]/td[2]/font[1]/b"), -4, -3);
		$freezeInt = (int)$freezeTime;
		$I->assertGreaterThanOrEqual($freezeInt, 8);

    }
}

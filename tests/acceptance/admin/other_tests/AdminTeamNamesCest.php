<?php


class AdminTeamNamesCest
{
    public function editTeamNameDisplay(AcceptanceTester $I)
    {
		$I->wantTo("Be able to turn on/off display team names to judge");
		$I->adminLogin("admin", "password");
		$I->checkOption("team_show");
		$I->click("B1");
		$I->judgeLogin("judge", "password");
		$I->amOnPage("judge/judge.php");
		$I->see("Team Name");
	    $I->adminLogin("admin", "password");
        $I->uncheckOption("team_show");
        $I->click("B1");
        $I->judgeLogin("judge", "password");
        $I->amOnPage("judge/judge.php");
        $I->dontSee("Team Name");	
		$I->see("Team ID");
	
    }
}

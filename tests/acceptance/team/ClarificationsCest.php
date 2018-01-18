<?php


class ClarificationsCest
{
    public function tryToTest(AcceptanceTester $I)
    {	
		$I->wantTo("Add clarifications so that teams can view them");
		$I->judgeLogin('judge', 'password');
		$I->amOnPage("/judge/clarifications.php");
		$I->click("Make new Clarification");
		$I->fillField("response", "This is a test clarification");
		$I->click("submit");
		$I->teamLogin("Test-Team", "test");
		$I->amOnPage("/clarifications.php");
		$I->seeInPageSource("<td colspan=2 bgcolor=#dddddd>This is a test clarification</td>");
    }
}

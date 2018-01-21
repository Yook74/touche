<?php

use \Codeception\Util\Locator;

class UserRequestClarificationCest
{
    public function userClarification(AcceptanceTester $I)
    {
        $I->wantTo("Request clarifications and respond to them with judge");
        $I->teamLogin("Test-Team", "test");
        $I->amOnPage("/clarifications.php");
		$I->click('Request Clarification');
		$I->fillField("question", "this is a test question");
		$I->click("submit");
		$I->judgeLogin("judge", "password");
		$I->amOnPage("/judge/clarifications.php");
		$I->see("this is a test question");
		$I->click( 'Respond to Clarification');
		$I->fillField("response", "Test response to test clarification");
		$I->click('submit');
	    $I->teamLogin("Test-Team", "test");
        $I->amOnPage("/clarifications.php");
		$I->see("Test response to test clarification");
	
		
    }
}

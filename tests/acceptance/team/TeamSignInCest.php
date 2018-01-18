<?php


class TeamSignInCest
{
    public function tryToTest(AcceptanceTester $I)
    {
		$I->wantTo("Sign In as a Team");
		$I->teamLogin("Test-Team", "test");
		
    }
}

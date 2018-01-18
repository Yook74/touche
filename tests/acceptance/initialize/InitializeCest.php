<?php


class InitializeCest
{
    public function tryToTest(AcceptanceTester $I)
    {
		$I->wantTo("Intialize Tests");
		$I->adminLogin('admin', 'password');
		$I->addSite("test-site");
		$I->addTeam("Test-Team","test-org","Test-Team","test","test-site","contestant1", "contestant2", "contestant3", "alternate", "email", "coach");
		$I->startContest();
		
    }
}

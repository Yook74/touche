<?php


class JudgeSignInCest
{
    public function tryToTest(AcceptanceTester $I)
    {
		$I->wantTo("Sign in as a judge");
		$I->judgeLogin("judge", "password");
    }
}

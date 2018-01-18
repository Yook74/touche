<?php


class AdminAddJudgeCest
{
    public function JudgeCrentials(AcceptanceTester $I)
    {
		$I->wantTo('Change Judge Credentials and login with Judge');
        $I->adminLogin('admin','password');
        $I->fillField("username", "testJudge");
        $I->fillField("password", "judgepassword");
        $I->click("B1");
		$I->judgeLogin('testJudge', 'judgepassword');
		$I->adminLogin('admin','password');
        $I->fillField("username", "judge");
        $I->fillField("password", "password");
        $I->click("B1");	
    }
}

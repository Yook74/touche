<?php


class SetupCest
{
    public function editFreezeTime(AdminActor $I)
    {
        $I->wantTo("Edit Frozen Standings Duration and ensure the change effects other users [STUB]");
    }
    public function editContestName(AdminActor $I)
    {
        $I->wantTo('Edit the host name and contest name');
        /*
        $host_name = "edit_host"; #TODO
        $contest_name = "edit_name";

        $I->amOnPage("/admin/setup_contest.php");
        $I->fillField("contest_host", $host_name);
        $I->fillField("contest_name", $contest_name);
        $I->click("B1");
        $observed_c_name =	$I->grabTextFrom("/html/body/table/tbody/tr/td/table/tbody/tr[1]/td[1]/font/b");
        $I->assertEquals($observed_c_name, $contest_name);
        $observed_h_name = $I->grabTextFrom("/html/body/table/tbody/tr/td/table/tbody/tr[1]/td[1]/font/small");
        $I->assertEquals($observed_h_name, $host_name);
        */
        ;
    }

    public function setJudgeCredentials(AdminActor $I)
    {
        $I->wantTo('Change Judge Credentials');
        $I->setJudgeCredentials();
    }

    /**
     * @depends setJudgeCredentials
     */
    public function testJudgeLogin(JudgeActor $I)
    {
        $I->wantTo('Try to log in as Judge');
        $I->seeInCurrentUrl("main.php"); # Logged in with the constructor
    }
}

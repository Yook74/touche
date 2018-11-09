<?php


class SetupCest
{
    public function addProblem(AdminActor $I)
    {
        $problem_name = "Add Two Numbers"; #TODO not sure how to parametrize this
        $problem_dir = "add";
		$I->wantTo("Make sure the admin can add problems");
		$I->amOnPage("/admin/setup_problems.php");
		$I->fillField("problem_name", $problem_name);
		$I->fillField("problem_loc", $problem_dir);
		$I->fillField("problem_note", "This is just a test");
		$I->click('submit');
		$I-> see($problem_name);
    }
    public function editFreezeTime(AdminActor $I)
    {
        $I->wantTo("Edit Frozen Standings Duration and ensure the change effects other users");
    }
    public function editContestName(AdminActor $I)
    {
        $host_name = "edit_host"; #TODO
        $contest_name = "edit_name";

        $I->wantTo('Edit the host name and contest name');
        $I->amOnPage("/admin/setup_contest.php");
        $I->fillField("contest_host", $host_name);
        $I->fillField("contest_name", $contest_name);
        $I->click("B1");
        $observed_c_name =	$I->grabTextFrom("/html/body/table/tbody/tr/td/table/tbody/tr[1]/td[1]/font/b");
        $I->assertEquals($observed_c_name, $contest_name);
        $observed_h_name = $I->grabTextFrom("/html/body/table/tbody/tr/td/table/tbody/tr[1]/td[1]/font/small");
        $I->assertEquals($observed_h_name, $host_name);
    }
    public function setJudgeCredentials(AdminActor $I)
    {
        $I->wantTo('Change Judge Credentials');
        $I->fillField("username", JudgeActor::$default_user);
        $I->fillField("password", JudgeActor::$default_pass);
        $I->click("B1");
    }
}

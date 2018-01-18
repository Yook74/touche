<?php


class AdminEditContestNameCest
{
    // tests
    public function editContestName(AcceptanceTester $I)
    {
		$I->wantTo('Edit the host name and contest name');
		$I->adminLogin('admin','password');
		$I->fillField("contest_host", "edit_host");
		$I->fillField("contest_name", "edit_name");
		$I->click("B1");
		$contest_name =	$I->grabTextFrom("/html/body/table/tbody/tr/td/table/tbody/tr[1]/td[1]/font/b");
		$I->assertEquals($contest_name, "edit_name");
		$contest_host = $I->grabTextFrom("/html/body/table/tbody/tr/td/table/tbody/tr[1]/td[1]/font/small");
		$I->assertEquals($contest_host, "edit_host");
		$I->fillField("contest_host", "host_name");
        $I->fillField("contest_name", "contest_name");
		$I->click("B1");

    }
}

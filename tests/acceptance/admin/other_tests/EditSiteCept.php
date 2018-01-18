<?php
use Codeception\Util\Locator; 
$I = new AcceptanceTester($scenario);
$I->wantTo('Edit a site');
$I->adminLogin('admin','password');
$I->amOnPage('admin/setup_site.php');
$siteID = $I->grabFromDatabase('SITE', 'SITE_ID', array('SITE_NAME' => 'test-site'));
$I->click( Locator::href("setup_site.php?site_id=$siteID" ));
$I->fillField("site_name", 'change-site');
$I->click('submit');
$I->judgeLogin('judge', 'password');
$I->amOnPage('/judge/start.php');
$I->see('change-site');


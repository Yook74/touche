<?php 
$I = new AcceptanceTester($scenario);
$I->wantTo('Ensure admin can login and create a contest');
$I->indexLogin('create','contest');
$I->createContest('test-host','test-name','password');

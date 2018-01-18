<?php 
$I = new AcceptanceTester($scenario);
$I->wantTo('Ensure admin can login and create a contest');
$I->adminLogin('admin','password');

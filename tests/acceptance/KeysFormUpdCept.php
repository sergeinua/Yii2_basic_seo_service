<?php 
$I = new AcceptanceTester($scenario);
$I->wantTo('group_id equals null parameter given on the keys form');
$I->amOnPage('index.php?r=keys%2Fupdate&id=26');
//$I->selectOption('select', '1');
//$I->click('.btn');
$I->click("button[type=submit]");
$I->see('Bad Request (#400)', 'h1' );

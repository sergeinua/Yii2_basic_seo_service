<?php 
$I = new AcceptanceTester($scenario);
$I->wantTo('perform actions and see result');
$I->amOnPage('/index.php?r=keys%2Fview&id=7');
$I->see('сайт под ключ киев', 'h1');

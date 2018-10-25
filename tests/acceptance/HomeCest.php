<?php

use yii\helpers\Url;

class HomeCest
{
    public function ensureThatHomePageWorks(AcceptanceTester $I)
    {
        $I->amOnPage(Url::toRoute('/site/index'));
        $I->see('Example App');
        
        $I->seeLink('Users');
        $I->click('Users');
        $I->wait(2);
        
        $I->see('Username');
        $I->see('Balance');
    }
}

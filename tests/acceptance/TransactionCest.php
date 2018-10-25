<?php

use yii\helpers\Url;

class TransactionCest
{
    public function _before(\AcceptanceTester $I)
    {
        $I->amOnPage(Url::toRoute('/site/login'));
        $I->see('Login', 'h1');

        $I->amGoingTo('try to login');
        $I->fillField('input[name="LoginForm[username]"]', 'user1');
        $I->click('login-button');
        $I->wait(2); // wait for button to be clicked

        $I->expectTo('see user info');
        $I->see('Logout');
    }
    
    public function transactionPageWorks(AcceptanceTester $I)
    {
        $I->seeLink('Transaction');
        $I->click('Transaction');
        $I->wait(2);

        $I->wantTo('ensure that transaction page works');
        $I->see('Transaction', 'h1');
    }

    public function transactionFormCanBeSubmitted(AcceptanceTester $I)
    {
        $I->amGoingTo('submit transaction form with correct data');
        $I->fillField('#transferform-username', 'user2');
        $I->fillField('#transferform-amount', '10');

        $I->click('transaction-button');
        $I->wait(2); // wait for button to be clicked

        $I->dontSeeElement('#transaction-form');
        $I->see('Success!! You have successfully transferred the amount of USD');
    }
}

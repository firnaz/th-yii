<?php

class TransferFormCest
{
    public function _before(\FunctionalTester $I)
    {
        $I->amOnPage(['site/login']);
    }

    public function openLoginPage(\FunctionalTester $I)
    {
        $I->see('Login', 'h1');
    }

    public function loginSuccessfully(\FunctionalTester $I)
    {
        $I->submitForm('#login-form', [
            'LoginForm[username]' => 'user1',
        ]);
        $I->see('Logout (user1)');
        $I->dontSeeElement('form#login-form');
    }

    public function openTransactionPage(\FunctionalTester $I)
    {
        $I->see('Transaction', 'h1');
    }

    public function submitEmptyForm(\FunctionalTester $I)
    {
        $I->submitForm('#transaction-form', []);
        $I->expectTo('see validations errors');
        $I->see('Transaction', 'h1');
        $I->see('Receipt cannot be blank');
        $I->see('Amount cannot be blank');
    }

    public function submitFormWithIncorrectAmount(\FunctionalTester $I)
    {
        $I->submitForm('#transaction-form', [
            'TransferForm[username]' => 'user2',
            'TransferForm[amount]' => '0',
        ]);
        $I->expectTo('Transfer amount must be greater than 0.');

        $I->submitForm('#transaction-form', [
            'TransferForm[username]' => 'user2',
            'TransferForm[amount]' => '-100',
        ]);
        $I->expectTo('Transfer amount must be greater than 0.');
    }

    public function submitFormWithAboveLimitAmount(\FunctionalTester $I)
    {
        $I->submitForm('#transaction-form', [
            'TransferForm[username]' => 'user2',
            'TransferForm[amount]' => '100000',
        ]);
        $I->expectTo('You cannot transfer that amount, this will exceed the minimum balance limit in your account.');
    }

    public function submitFormWithNonExistReceipt(\FunctionalTester $I)
    {
        $I->submitForm('#transaction-form', [
            'TransferForm[username]' => 'user3',
            'TransferForm[amount]' => '10',
        ]);
        $I->expectTo('Receipt\'s username doesn\'t exist.');
    }

    public function submitFormWithSelfReceipt(\FunctionalTester $I)
    {
        $I->submitForm('#transaction-form', [
            'TransferForm[username]' => 'user1',
            'TransferForm[amount]' => '10',
        ]);
        $I->expectTo('You cannot transfer to yourself.');
    }

    public function submitFormSuccessfully(\FunctionalTester $I)
    {
        $I->submitForm('#transaction-form', [
            'TransferForm[username]' => 'user2',
            'TransferForm[amount]' => '10',
        ]);
        $I->dontSeeElement('#transaction-form');
        $I->see('Success!! You have successfully transferred the amount of USD');
    }
}

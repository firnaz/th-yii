<?php

namespace tests\models;

class TransferFormTest extends \Codeception\Test\Unit
{
    private $model;

    private $loginForm;
    /**
     * @var \UnitTester
     */
    protected $tester;

    public function _before()
    {
        $this->loginForm = new \app\models\LoginForm([
            'username' => 'user1',
        ]);

        $this->assertTrue($this->loginForm->login());
    }

    public function testDoTransferWithEmptyValue()
    {
        $transferForm = new \app\models\TransferForm;
 
        $this->assertFalse($transferForm->doTransfer());
    }

    public function testDoTransferWithNoAmount()
    {
        $transferForm = new \app\models\TransferForm;
        $transferForm->load([
            "username" => "user2"
        ], '');

        $this->assertFalse($transferForm->doTransfer());
    }

    public function testDoTransferWithNoReceipt()
    {
        $transferForm = new \app\models\TransferForm;
        $transferForm->load([
            "amount" => "10"
        ], '');

        $this->assertFalse($transferForm->doTransfer());
    }

    public function testDoTransferWithZeroAmount()
    {
        $transferForm = new \app\models\TransferForm;
        $transferForm->load([
            "username" => "user2",
            "amount" => "0"
        ], '');

        $this->assertFalse($transferForm->doTransfer());
    }

    public function testDoTransferWithBigAmount()
    {
        $transferForm = new \app\models\TransferForm;
        $transferForm->load([
            "username" => "user2",
            "amount" => "9999999999"
        ], '');

        $this->assertFalse($transferForm->doTransfer());
    }

    public function testDoTransferWithNegativeAmount()
    {
        $transferForm = new \app\models\TransferForm;
        $transferForm->load([
            "username" => "user2",
            "amount" => "-10"
        ], '');

        $this->assertFalse($transferForm->doTransfer());
    }

    public function testDoTransferToNonExistingUser()
    {
        $transferForm = new \app\models\TransferForm;
        $transferForm->load([
            "username" => "non_existing_user",
            "amount" => "10"
        ], '');

        $this->assertFalse($transferForm->doTransfer());
    }

    public function testDoTransferToSelf()
    {
        $transferForm = new \app\models\TransferForm;
        $transferForm->load([
            "username" => "user1",
            "amount" => "10"
        ], '');

        $this->assertFalse($transferForm->doTransfer());
    }
    
    public function testDoTransferSuccess()
    {
        $sender = $this->tester->grabRecord('app\models\User', ["username" => 'user1']);
        $receipt = $this->tester->grabRecord('app\models\User', ["username" => 'user2']);

        $transferForm = new \app\models\TransferForm;

        $transferForm->load([
            "username" => "user2",
            "amount" => "10"
        ], '');

        $this->assertTrue($transferForm->doTransfer());

        $this->tester->seeRecord('app\models\User', ["username" => 'user2', "balance" => ($receipt->balance+10)]);
        $this->tester->seeRecord('app\models\User', ["username" => 'user1', "balance" => ($sender->balance-10)]);
    }
}

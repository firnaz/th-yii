<?php

namespace tests\models;

class TransferFormTest extends \Codeception\Test\Unit
{
    private $model;

    private $loginForm;
    /**
     * @var \UnitTester
     */
    public $tester;

    public function testDoTransfer()
    {
        $this->loginForm = new \app\models\LoginForm([
            'username' => 'user1',
        ]);

        expect_that($this->loginForm->login());


        /** @var TransferForm $model */
        $this->model = $this->getMockBuilder('app\models\TransferForm')
            ->setMethods(['doTransfer'])
            ->getMock();

        $this->model->attributes = [
            'username' => 'user2',
            'amount' => 10
        ];

        $this->model
            ->method('doTransfer')
            ->will($this->returnValue(true));

        expect_that($this->model->doTransfer());

        $this->model->attributes = [
            'username' => 'user3',
            'amount' => 10
        ];

        $this->model
            ->method('doTransfer')
            ->will($this->returnValue(true));

        $this->assertFalse($this->model->doTransfer());
    }
}

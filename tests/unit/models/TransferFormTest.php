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
        $this->loginForm = new LoginForm([
            'username' => 'user1',
        ]);

        expect_that($this->loginForm->login());


        /** @var TransferForm $model */
        $this->model = $this->getMockBuilder('app\models\TransferForm')
            ->setMethods(['validate'])
            ->getMock();

        $this->model->expects($this->once())
            ->method('validate')
            ->will($this->returnValue(true));

        $this->model->attributes = [
            'username' => 'user2',
            'amount' => 10
        ];

        expect_that($this->model->doTransfer());
    }
}

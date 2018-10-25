<?php

namespace tests\models;

use app\models\LoginForm;
use app\models\User;

class LoginFormTest extends \Codeception\Test\Unit
{
    private $model;


    protected function _after()
    {
        \Yii::$app->user->logout();
    }

    public function testLoginCorrect()
    {
        $this->model = new LoginForm([
            'username' => 'user1',
        ]);

        expect_that($this->model->login());
        expect_not(\Yii::$app->user->isGuest);
    }
}

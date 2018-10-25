<?php

namespace tests\models;

use app\models\User;

class UserTest extends \Codeception\Test\Unit
{
    public function testFindUserByUsername()
    {
        expect_that($user = User::findByUsername('user1'));
        expect_not(User::findByUsername('user3'));
    }
}

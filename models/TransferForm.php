<?php

namespace app\models;

use Yii;
use yii\base\Model;

class TransferForm extends Model
{
    public $username;
    public $amount;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // name, email, subject and body are required
            [['username', 'amount'], 'required'],
            [['amount'], 'number'],
            // validation rules
            ['username', 'validateUsername'],
            ['amount', 'validateAmount']
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'username' => 'Receipt',
            'amount' => 'Amount',
        ];
    }

    public function validateUsername($attribute, $params)
    {
        if (!$this->hasErrors()) {
            // check the receipt is one of existing user
            $user = User::find()->where(["username"=>$this->username])->one();
            if (!$user) {
                $this->addError($attribute, 'Receipt\'s username doesn\'t exist.');
            } else {
                // The receipt cannot same as the current logged in user
                $currentUser = \Yii::$app->user->getIdentity();
                if ($user->username == $currentUser->username) {
                    $this->addError($attribute, 'You cannot transfer to yourself.');
                }
            }
        }
    }

    public function validateAmount($attribute, $params)
    {
        if (!$this->hasErrors()) {
            // check amount must be greater than 0
            if ($this->amount <= 0) {
                $this->addError($attribute, 'Transfer amount must be greater than 0.');
            } else {
                $currentUser = \Yii::$app->user->getIdentity();
                // check current user balance after transfer, the balance must not be lower than -1000
                $minBalance = $currentUser->balance - $this->amount;
                if ($minBalance < -1000) {
                    $this->addError($attribute, 'You cannot transfer that amount, this will exceed the minimum balance limit in your account.');
                }
            }
        }
    }

    public function doTransfer()
    {
        if ($this->validate()) {
            $db = Yii::$app->db;
            $transaction = $db->beginTransaction();
            $currentUser = \Yii::$app->user->getIdentity();
            $receiptUser = User::find()->where(["username"=>$this->username])->one();
            try {
                // update receipt balance
                $sqlUpdateReceiptBalance = "UPDATE user SET balance='".($receiptUser->balance+$this->amount)."' WHERE username='".$receiptUser->username."'";
                $db->createCommand($sqlUpdateReceiptBalance)->execute();

                // update current user balance
                $sqlUpdateUserBalance = "UPDATE user SET balance='".($currentUser->balance-$this->amount)."' WHERE username='".$currentUser->username."'";
                $db->createCommand($sqlUpdateUserBalance)->execute();

                $transaction->commit();
            } catch (\Exception $e) {
                $transaction->rollBack();
                throw $e;
            } catch (\Throwable $e) {
                $transaction->rollBack();
                throw $e;
            }

            return true;
        }
        return false;
    }
}

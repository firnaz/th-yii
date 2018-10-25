<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use yii\data\Pagination;
use app\models\LoginForm;
use app\models\TransferForm;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'transaction'],
                'rules' => [
                    [
                        'actions' => ['logout', 'transaction'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post']
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays transaction page.
     *
     * @return Response|string
     */
    public function actionTransaction()
    {
        $model = new TransferForm();
        if ($model->load(Yii::$app->request->post()) && $model->doTransfer()) {
            Yii::$app->session->setFlash('transferSuccess');
            Yii::$app->session->setFlash('username', $model->username);
            Yii::$app->session->setFlash('amount', number_format($model->amount, 2));
            return $this->refresh();
        }
        return $this->render('transaction', [
            'model' => $model,
        ]);
    }

    /**
     * Displays Users page.
     *
     * @return string
     */
    public function actionUsers()
    {
        // get page request;
        $page = Yii::$app->request->get("page")>0?Yii::$app->request->get("page"):1;

        // get the total number of users
        $countUser = \app\models\User::find()->count();

        // create a pagination object with the total count
        $pagination = new Pagination(['totalCount' => $countUser, 'page'=>($page-1)]);

        $users = \app\models\User::find()
            ->orderBy("username ASC")
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();

        return $this->render('users', ["users" => $users, "pagination"=>$pagination]);
    }
}

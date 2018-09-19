<?php

namespace app\controllers;

use Yii;
use app\models\Users;
use app\models\UserSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\ForbiddenHttpException;
use app\models\AuthItem;
use yii\helpers\ArrayHelper;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
/**
 * UserController implements the CRUD actions for Users model.
 */
class UserController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            'access' => [
                'class'=> AccessControl::className(),
                'only' => ['update', 'delete'],
            
            'rules' => [
                    [
                        'allow'=> true,
                        'actions' => ['update', 'delete'],
                        'roles' => ['@']
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Users models.
     * @return mixed
     */
    public function actionIndex()
    {
        if (Yii::$app->user->can('admin')) 
        {
            $searchModel = new UserSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        } else 
        {
            throw new ForbiddenHttpException("Access denied");
        }

    }

    /**
     * Displays a single Users model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        if(Yii::$app->user->can('update-user') || Yii::$app->user->identity->id == $id) 
        {
            return $this->render('view', [
                'model' => $this->findModel($id),
            ]);            
        } else {
            throw new ForbiddenHttpException("Access denied");
        }

    }

    /**
     * Creates a new Users model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Users();
        $model->hashPassword = true;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $model->addPerm();
            //мейл с проверочной ссылкой
            Yii::$app->mailer->compose('register', compact('model'))->setFrom('myyiiserver@gmail.com')->setTo('kartsianovich@gmail.com')->setSubject('Welcome to the Yiisite!')->send();
            return $this->redirect(['site/index']);
        }


        return $this->render('create', [
            'model' => $model, 

        ]);
    }

    /**
     * Updates an existing Users model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {

        if(Yii::$app->user->can('admin')) 
        {
            $model = $this->findModel($id);
            $model->hashPassword = true;
            $privAdmin = AuthItem::findOne(['name'=> 'admin']);
            $privUser = AuthItem::findOne(['name'=> 'user']);
            $authItems = [$privAdmin, $privUser];
            $authItems = ArrayHelper::map($authItems, 'name', 'name');

            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }

            return $this->render('update', [
                'model' => $model, 'authItems' => $authItems
            ]);            
        } else if (Yii::$app->user->identity->id == $id)

        {
            $model = $this->findModel($id);
            return $this->render('_form-update-self', [
                'model' => $model
            ]);
            
        } else 
        {
            throw new ForbiddenHttpException("Access denied");
        }

    }

    /**
     * Deletes an existing Users model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        if (Yii::$app->user->can('delete-user'))
        {
            $this->findModel($id)->delete();

            return $this->redirect(['index']);
        } else 
        {
            throw new ForbiddenHttpException("Action denied");
        }

    }

    /**
     * Finds the Users model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Users the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Users::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionSendEmail()
    {
        $model = new \app\models\SendEmailForm();

        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                if($model->sendEmail()) 
                {
                    Yii::$app->getSession()->setFlash('warning', 'Check your email');
                    return $this->goHome();
                } else 
                {

                    Yii::$app->getSession()->setFlash('error', 'Some error just occured.');
                }
            }
        }

        return $this->render('SendEmail', [
            'model' => $model,
        ]);
    }

    public function actionRetreivePasswordForm($key)
    {

        try 
        {
            $model = new \app\models\RetreivePasswordForm($key);
        }

        catch (InvalidParamException $e)
        {
            throw new BadRequestHttpException($e->getMessage());
        }
        

        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate() && $model->resetPassword()) {
                Yii::$app->getSession()->setFlash('warning', 'Password is successfully changed');
                return $this->redirect(['../site/login']);
            }
        }

        return $this->render('RetreivePasswordForm', [
            'model' => $model,
        ]);
    }

}

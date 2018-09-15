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
        $hashPassword = true;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {

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
}

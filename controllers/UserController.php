<?php

namespace app\controllers;

use app\models\Task;
use Yii;
use app\models\User;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
{
  /**
   * {@inheritdoc}
   */
  public function behaviors()
  {
    return [
      'access' => [
        'class' => AccessControl::className(),
        'rules' => [
          [
            'allow' => true,
            'roles' => ['@'],
          ],
        ],
      ],
      'verbs' => [
        'class' => VerbFilter::className(),
        'actions' => [
          'delete' => ['POST'],
        ],
      ],
    ];
  }

  /**
   * Lists all User models.
   * @return mixed
   */
  public function actionIndex()
  {
    $dataProvider = new ActiveDataProvider([
      'query' => User::find(),
    ]);

    return $this->render('index', [
      'dataProvider' => $dataProvider,
    ]);
  }

  /**
   * Displays a single User model.
   * @param integer $id
   * @return mixed
   * @throws NotFoundHttpException if the model cannot be found
   */
  public function actionView($id)
  {
    return $this->render('view', [
      'model' => $this->findModel($id),
    ]);
  }

  /**
   * Creates a new User model.
   * If creation is successful, the browser will be redirected to the 'view' page.
   * @return mixed
   */
  public function actionCreate()
  {
    $model = new User();
    if ($model->load(Yii::$app->request->post()) && $model->save()) {
      return $this->redirect(['view', 'id' => $model->id]);
    }

    return $this->render('create', [
      'model' => $model,
    ]);
  }

  /**
   * Updates an existing User model.
   * If update is successful, the browser will be redirected to the 'view' page.
   * @param integer $id
   * @return mixed
   * @throws NotFoundHttpException if the model cannot be found
   */
  public function actionUpdate($id)
  {
    $model = $this->findModel($id);

    if ($model->load(Yii::$app->request->post()) && $model->save()) {
      return $this->redirect(['view', 'id' => $model->id]);
    }

    return $this->render('update', [
      'model' => $model,
    ]);
  }

  /**
   * Deletes an existing User model.
   * If deletion is successful, the browser will be redirected to the 'index' page.
   * @param integer $id
   * @return mixed
   * @throws NotFoundHttpException if the model cannot be found
   */
  public function actionDelete($id)
  {
    $this->findModel($id)->delete();

    return $this->redirect(['index']);
  }

  public function actionTest()
  {
    $model = new User();
    $model->setAttributes([
      'username' => 'Nicolas',
      'password_hash' => 'teriut98',
      'creator_id' => 4,
      'created_at' => time(),
    ]);
    $model->save();

    $model = User::findOne(1);
    $task = new Task();
    $task->title = 'dinner';
    $task->description = 'meat';
    $task->created_at = time();
    $task->link(User::RELATION_CREATED_TASKS, $model);
    $model = User::findOne(2);
    $task = new Task();
    $task->title = 'sport';
    $task->description = 'football';
    $task->created_at = time();
    $task->link(User::RELATION_CREATED_TASKS, $model);
    $model = User::findOne(4);
    $task = new Task();
    $task->title = 'bar';
    $task->description = 'jack';
    $task->created_at = time();
    $task->link(User::RELATION_CREATED_TASKS, $model);

    $models = User::find()->with(User::RELATION_CREATED_TASKS)->all();
    foreach($models as $item) {
      _log($item->getCreatedTasks());
    }

    $models = User::find()->joinWith(User::RELATION_CREATED_TASKS)->all();
    foreach($models as $item) {
      _log($item->getCreatedTasks());
    }

    $model = User::findOne(2);
    _end($model->getAccessedTasks()->asArray()->all());
  }

  /**
   * Finds the User model based on its primary key value.
   * If the model is not found, a 404 HTTP exception will be thrown.
   * @param integer $id
   * @return User the loaded model
   * @throws NotFoundHttpException if the model cannot be found
   */
  protected function findModel($id)
  {
    if (($model = User::findOne($id)) !== null) {
      return $model;
    }

    throw new NotFoundHttpException('The requested page does not exist.');
  }
}

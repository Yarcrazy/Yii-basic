<?php

namespace app\controllers;

use app\models\Task;
use app\models\User;
use Yii;
use app\models\TaskUser;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TaskUserController implements the CRUD actions for TaskUser model.
 */
class TaskUserController extends Controller
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
    ];
  }

  /**
   * Lists all TaskUser models.
   * @return mixed
   */
  public function actionIndex()
  {
    $dataProvider = new ActiveDataProvider([
      'query' => TaskUser::find(),
    ]);

    return $this->render('index', [
      'dataProvider' => $dataProvider,
    ]);
  }

  /**
   * Displays a single TaskUser model.
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
   * Creates a new TaskUser model.
   * If creation is successful, the browser will be redirected to the 'view' page.
   * @param $taskId integer
   * @return mixed
   * @throws ForbiddenHttpException
   */
  public function actionCreate($taskId)
  {
    $task = Task::findOne($taskId);
    if (!$task || $task->creator_id != Yii::$app->user->id) {
      throw new ForbiddenHttpException();
    }

    $model = new TaskUser();
    $model->task_id = $taskId;

    if ($model->load(Yii::$app->request->post()) && $model->save()) {
      Yii::$app->session->setFlash('success', 'Task shared successfully!');
      return $this->redirect(['task/my']);
    }

    $users = User::find()->where(['<>', 'id', Yii::$app->user->id])->select('username')
      ->indexBy('id')->column();
//    $users = TaskUser::find()->where(['task_id' => $taskId])->select('user_id')
//      ->all();
//    _end($users);
    return $this->render('create', [
      'users' => $users,
      'model' => $model,
    ]);
  }

  /**
   * Updates an existing TaskUser model.
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
   * Deletes an existing TaskUser model.
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

  /**
   * Finds the TaskUser model based on its primary key value.
   * If the model is not found, a 404 HTTP exception will be thrown.
   * @param integer $id
   * @return TaskUser the loaded model
   * @throws NotFoundHttpException if the model cannot be found
   */
  protected function findModel($id)
  {
    if (($model = TaskUser::findOne($id)) !== null) {
      return $model;
    }

    throw new NotFoundHttpException('The requested page does not exist.');
  }
}

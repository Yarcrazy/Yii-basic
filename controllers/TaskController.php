<?php

namespace app\controllers;

use Yii;
use app\models\Task;
use yii\data\ActiveDataProvider;
use yii\web\ConflictHttpException;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TaskController implements the CRUD actions for Task model.
 */
class TaskController extends Controller
{
  public $defaultAction = 'my';
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
   * Lists all Task models.
   * @return mixed
   */
  public function actionMy()
  {
    $dataProvider = new ActiveDataProvider([
      'query' => Task::find()->byCreator(Yii::$app->user->id),
    ]);

    return $this->render('my', [
      'dataProvider' => $dataProvider,
    ]);
  }

  /**
   * Lists shared Task models.
   * @return mixed
   */
  public function actionShared()
  {
    $dataProvider = new ActiveDataProvider([
      'query' => Task::find()->innerJoinWith(Task::RELATION_TASK_USERS),
    ]);

    return $this->render('shared', [
      'dataProvider' => $dataProvider,
    ]);
  }

  /**
   * Lists accessed Task models.
   * @return mixed
   */
  public function actionAccessed()
  {
    $dataProvider = new ActiveDataProvider([
      'query' => Task::find()->innerJoinWith(Task::RELATION_TASK_USERS)
      ->where(['user_id' => Yii::$app->user->id]),
    ]);

    return $this->render('accessed', [
      'dataProvider' => $dataProvider,
    ]);
  }

  /**
   * Displays a single Task model.
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
   * Creates a new Task model.
   * If creation is successful, the browser will be redirected to the 'view' page.
   * @return mixed
   */
  public function actionCreate()
  {
    $model = new Task();

    if ($model->load(Yii::$app->request->post()) && $model->save()) {
      Yii::$app->session->setFlash('success', 'Task created successfully!');
      return $this->redirect(['my']);
    }

    return $this->render('create', [
      'model' => $model,
    ]);
  }

  /**
   * Updates an existing Task model.
   * If update is successful, the browser will be redirected to the 'view' page.
   * @param integer $id
   * @return mixed
   * @throws NotFoundHttpException if the model cannot be found
   * @throws ConflictHttpException if login user is not creator
   */
  public function actionUpdate($id)
  {
    $model = $this->findModel($id);

    if ($model->creator_id != Yii::$app->user->id) {
      throw new ConflictHttpException('No access to this task');
    }
    if ($model->load(Yii::$app->request->post()) && $model->save()) {
      Yii::$app->session->setFlash('success', 'Task updated successfully!');
      return $this->redirect(['my']);
    }

    return $this->render('update', [
      'model' => $model,
    ]);
  }

  /**
   * Deletes an existing Task model.
   * If deletion is successful, the browser will be redirected to the 'my' page.
   * @param integer $id
   * @return mixed
   * @throws NotFoundHttpException if the model cannot be found
   * @throws ConflictHttpException if login user is not creator
   */
  public function actionDelete($id)
  {
    $model = $this->findModel($id);
    if ($model->creator_id != Yii::$app->user->id) {
      throw new ConflictHttpException('No access to this task');
    }
    Yii::$app->session->setFlash('success', 'Task deleted successfully!');
    $model->delete();

    return $this->redirect(['my']);
  }

  /**
   * Finds the Task model based on its primary key value.
   * If the model is not found, a 404 HTTP exception will be thrown.
   * @param integer $id
   * @return Task the loaded model
   * @throws NotFoundHttpException if the model cannot be found
   */
  protected function findModel($id)
  {
    if (($model = Task::findOne($id)) !== null) {
      return $model;
    }

    throw new NotFoundHttpException('The requested page does not exist.');
  }
}

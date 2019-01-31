<?php

namespace app\controllers;

use app\models\Product;
use Yii;
use yii\db\Query;
use yii\helpers\VarDumper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class TestController extends Controller
{
  public function actionIndex()
  {
    $product = new Product();
    $product->id = 1;
    $product->category = 'category';
    $product->name = 'name';
    $product->price = 300;

    $data = Yii::$app->test->prop;
    return $this->render('index', ['data' => $data]);
  }

  public function actionInsert()
  {
    Yii::$app->db->createCommand()->insert('user', [
      'username' => 'John',
      'password_hash' => 'ghwo8rsdf',
      'creator_id' => 1,
      'created_at' => time(),
    ])->execute();
    Yii::$app->db->createCommand()->insert('user', [
      'username' => 'Malcolm',
      'password_hash' => 'rtui45iu',
      'creator_id' => 2,
      'created_at' => time(),
    ])->execute();
    Yii::$app->db->createCommand()->insert('user', [
      'username' => 'Peter',
      'password_hash' => 'sdf34ffd',
      'creator_id' => 3,
      'created_at' => time(),
    ])->execute();

    Yii::$app->db->createCommand()->batchInsert('task', ['title', 'description', 'creator_id', 'created_at'], [
      ['Cinema', 'Alien', 1, time()],
      ['Theatre', 'Gamlet', 2, time()],
      ['Meeting', 'Mary', 3, time()],
    ])->execute();
  }

  public function actionSelect()
  {
    $result = (new Query())->from('user')->where(['id' => 1])->all();
    _log($result);

    $result = (new Query())->from('user')->where(['>', 'id', 1])->orderBy('username')->all();
    _log($result);

    $result = (new Query())->select('count(1)')->from('user')->all();
    _log($result);

    $result = (new Query())->from('task')->innerJoin('user', 'task.creator_id = user.creator_id')->all();
    _log($result);
  }
}
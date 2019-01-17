<?php

namespace app\controllers;

use app\models\Product;
use Yii;
use yii\web\Controller;

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
}
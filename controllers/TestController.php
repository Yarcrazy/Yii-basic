<?php
/**
 * Created by IntelliJ IDEA.
 * User: BigMitch
 * Date: 11.01.2019
 * Time: 23:47
 */

namespace app\controllers;


use app\models\Product;
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
    return $this->render('index', ['product' => $product]);
  }
}
<?php
/**
 * @var $product \app\models\Product
 * @var $this yii\web\View
 */
echo $product->price;
echo \yii\widgets\DetailView::widget(['model' => $product]);



?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Test</title>
</head>
<body>
<h1>Test for product</h1>
<h2>Begin</h2>
<h2>End</h2>
</body>
</html>
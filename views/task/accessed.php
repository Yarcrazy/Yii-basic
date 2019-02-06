<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Accessed tasks';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="task-index">

	<h1><?= Html::encode($this->title) ?></h1>

	<p>
    <?= Html::a('Create Task', ['create'], ['class' => 'btn btn-success']) ?>
	</p>

  <?= GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
      ['class' => 'yii\grid\SerialColumn'],

      'title',
      'description:ntext',
      'creator.username',
			'created_at:datetime',

      ],
  ]); ?>
</div>

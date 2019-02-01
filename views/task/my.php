<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Tasks';
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
      'created_at:datetime',
      'updated_at:datetime',

      ['class' => 'yii\grid\ActionColumn',
        'template' => '{share} {view} {update} {delete}',
        'buttons' =>
          [
            'share' => function ($url, $model, $key) {
              $icon = \yii\bootstrap\Html::icon('share');
              return Html::a($icon, ['task-user/create', 'taskId' => $model]);
            }
          ],
      ],
    ],
  ]); ?>
</div>

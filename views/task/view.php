<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Task */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Tasks', 'url' => ['my']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="task-view">

	<h1><?= Html::encode($this->title) ?></h1>

	<p>
    <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
    <?= Html::a('Delete', ['delete', 'id' => $model->id], [
      'class' => 'btn btn-danger',
      'data' => [
        'confirm' => 'Are you sure you want to delete this item?',
        'method' => 'post',
      ],
    ]) ?>
	</p>

  <?= DetailView::widget([
    'model' => $model,
    'attributes' => [
      'title',
      'description:ntext',
      'creator_id',
      'updater_id',
      'created_at:datetime',
      'updated_at:datetime',
    ],
  ]); ?>

  <?= GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
      'user.username',

      ['class' => 'yii\grid\ActionColumn',
        'template' => '{unshare}',
        'buttons' =>
          [
            'unshare' => function ($url, $model, $key) {
              $icon = \yii\bootstrap\Html::icon('remove');
              return Html::a($icon, ['task-user/delete', 'id' => $model->id], [
                'data' => [
                  'confirm' => 'Are you sure want to unshare this task to user?',
                  'method' => 'post',
                ],
              ]);
            }
          ],
      ],
    ],
  ]); ?>

</div>

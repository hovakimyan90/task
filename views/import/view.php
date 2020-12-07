<?php

use app\models\Import;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Import */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Imports', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="import-view">

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
            'id',
            [
                'attribute' => 'store_id',
                'label' => 'Store',
                'value' => function ($model)
                {
                    return $model->store->title;
                }
            ],
            'filename',
            [
                'attribute' => 'status',
                'label' => 'Status',
                'value' => function ($model) {
                    return Import::getStatus($model->status);
                }
            ],
            'success_count',
            'failed_count',
        ],
    ]) ?>

</div>

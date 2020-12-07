<?php

use app\models\Import;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Import';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="import-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Import', ['create'], ['class' => 'btn btn-success']) ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

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

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>

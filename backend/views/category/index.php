<?php
/* @var $this yii\web\View */
?>
<h1>商品分类</h1>

<?= \leandrogehlen\treegrid\TreeGrid::widget([
    'dataProvider' => $dataProvider,
    'keyColumnName' => 'id',
    'parentColumnName' => 'parent_id',
    'parentRootValue' => '0', //first parentId value
    'pluginOptions' => [
        'initialState' => 'collapsed',
    ],
    'columns' => [
        'name',
        'id',
        'parent_id',
        ['class' => 'yii\grid\ActionColumn']
    ]
]); ?>

<br/>
<a href="<?=\yii\helpers\Url::to(["category/add"])?>" class="btn btn-info">添加</a>
<?php
/* @var $this yii\web\View */

?>
<h1>图书分类管理</h1>
<table class="table">
    <tr>
        <th>id</th>
        <th>名称</th>
        <th>是否是帮助类</th>
        <th>排序</th>
        <th>状态</th>
        <th>简介</th>

    </tr>
    <?php foreach ($articles as $data):?>
        <tr>
            <td><?=$data->id?></td>
            <td><?=$data->name?></td>
            <td><?=\backend\models\ArticleCategory::$as[$data->is_help]?></td>
            <td><?=$data->sort?></td>
            <td><?=\backend\models\Brand::$is[$data->status]?></td>
            <td><?=$data->intro?></td>
            <td>

                <a href="<?=yii\helpers\Url::to(['article-category/edit','id'=>$data->id])?>" class="btn btn-success">编辑</a>
                <a href="<?=yii\helpers\Url::to(['article-category/del','id'=>$data->id])?>" class="btn btn-danger">删除</a>

            </td>
        </tr>
    <?php endforeach; ?>
</table>

<?=
\yii\widgets\LinkPager::widget([
    "pagination" => $page
])
?>
<br/>




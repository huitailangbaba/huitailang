<?php
/* @var $this yii\web\View */

?>
<h1>品牌管理</h1>
<table class="table">
    <tr>
        <th>id</th>
        <th>名称</th>
        <th>LOGO</th>
        <th>排序</th>
        <th>状态</th>
        <th>简介</th>

    </tr>
    <?php foreach ($brands as $data):?>
        <tr>
            <td><?=$data->id?></td>
            <td><?=$data->name?></td>
            <td><?php
                $imgPath=strpos($data->logo,'ttp://')?$data->logo:"/".$data->logo;
                echo \yii\bootstrap\Html::img($imgPath,['height'=>30]);
                ?>
            </td>
            <td><?=$data->sort?></td>
            <td><?=\backend\models\Brand::$is[$data->status]?></td>
            <td><?=$data->intro?></td>
            <td>

                <a href="<?=yii\helpers\Url::to(['brand/edit','id'=>$data->id])?>" class="btn btn-success">编辑</a>
                <a href="<?=yii\helpers\Url::to(['brand/del','id'=>$data->id])?>" class="btn btn-danger">删除</a>

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




<?php
/* @var $this yii\web\View */

?>
<h1>文章管理</h1>
<table class="table">
    <tr>
        <th>id</th>
        <th>名称</th>
        <th>分类</th>
        <th>排序</th>
        <th>状态</th>
        <th>时间</th>
        <th>简介</th>

    </tr>
    <?php foreach ($articles as $data):?>
        <tr>
            <td><?=$data->id?></td>
            <td><?=$data->name?></td>
            <td><?=$data->data->name?></td>
            <td><?=$data->sort?></td>
            <td><?php
                if($data->status==1){

                    echo "<a class='glyphicon glyphicon-ok''</a>";
                }else{
                    echo "<a class='glyphicon glyphicon-remove'></a>";
                }
                ?></td>
            <td><?=date("Y-m-d H:i:s",$data->create_time)?></td>
            <td><?=$data->intro?></td>
            <td>

                <a href="<?=yii\helpers\Url::to(['article/edit','id'=>$data->id])?>" class="btn btn-success">编辑</a>
                <a href="<?=yii\helpers\Url::to(['article/del','id'=>$data->id])?>" class="btn btn-danger">删除</a>

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
<a href="<?=\yii\helpers\Url::to(["article/add"])?>" class="btn btn-info">添加</a>




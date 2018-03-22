<?php
/* @var $this yii\web\View */
?>
<h1>管理员首页</h1>

<table class="table">
    <tr>
        <th>id</th>
        <th>姓名</th>
        <th>头像</th>
        <th>电子邮箱</th>
        <th>创建时间</th>
        <th>操作</th>
    </tr>
    <?php foreach ($admins as $admin):?>
        <tr>
            <td><?=$admin->id?></td>
            <td><?=$admin->name?></td>
            <td><?php
                $imgPath=strpos($admin->img,'ttp://')?$admin->img:"/".$admin->img;
                echo \yii\bootstrap\Html::img($imgPath,['height'=>30]);
                ?>
            </td>
            <td><?=$admin->email?></td>
            <td><?=date("Y-m-d H:i:s",$admin->create_time)?></td>
            <td>
                <a href="<?=yii\helpers\Url::to(['admin/edit','id'=>$admin->id])?>" class="btn btn-success">编辑</a>
                <a href="<?=yii\helpers\Url::to(['admin/del','id'=>$admin->id])?>" class="btn btn-danger">删除</a>

            </td>
        </tr>
    <?php endforeach; ?>
</table>

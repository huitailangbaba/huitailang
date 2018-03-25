<?php
/* @var $this yii\web\View */
?>
<h1>权限角色首页</h1>

<table class="table">
    <tr>
        <th>姓名</th>
        <th>简介</th>
        <th>权限</th>
        <th>操作</th>
    </tr>
    <?php foreach ($roles as $role):?>
        <tr>
            <td><?=$role->name?></td>
            <td><?=$role->description?></td>
            <td>
                <?php
                //得到当前角色的搜优权限
                $auth = \Yii::$app->authManager;

             $pers =  $auth->getPermissionsByRole($role->name);
             $html ="";
             foreach ($pers as $pes){
                  $html.= $pes->description.",";
                }
                $html=trim($html,",");
echo $html;

                ?>

            </td>
            <td>
                <a href="<?=yii\helpers\Url::to(['edit','name'=>$role->name])?>" class="btn btn-success">编辑</a>
                <a href="<?=yii\helpers\Url::to(['del','name'=>$role->name])?>" class="btn btn-danger">删除</a>

            </td>
        </tr>
    <?php endforeach; ?>
</table>
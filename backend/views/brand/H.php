<h1>回收管理</h1>
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
            <td><img src="<?=Yii::getAlias("@web")."/".$data->logo?>" height="28"></td>
            <td><?=$data->sort?></td>
            <td><?=\backend\models\Brand::$is[$data->status]?></td>
            <td><?=$data->intro?></td>
            <td>

                <a href="<?=yii\helpers\Url::to(['brand/f','id'=>$data->id])?>" class="btn btn-success">还原</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

<?=
\yii\widgets\LinkPager::widget([
    "pagination" => $page
])
?>
<a href="<?=\yii\helpers\Url::to(["brand/index"])?>" class="btn btn-info">首页</a>



<?php
/* @var $this yii\web\View */
?>
<h1>商品管理</h1>

<form class="form-inline pull-right">
    <select class="form-control" name="status">
        <option>请选择</option>
        <option value="1" <?=\Yii::$app->request->get("status")==="1"?"selected":""?>>上线</option>
        <option value="2" <?=\Yii::$app->request->get("status")==="2"?"selected":""?>>下线</option>
    </select>
    <div class="form-group">
        <input type="text" class="form-control" id="minshop" placeholder="最低价格" size="5" name="minshop" value="<?=\Yii::$app->request->get("minshop")?>">
    </div>
    -
    <div class="form-group">
        <input type="text" class="form-control" id="maxshop" placeholder="最高价格" size="5" name="maxshop" value="<?=\Yii::$app->request->get("maxshop")?>">
    </div>
    <div class="form-group">
        <input type="text" class="form-control" id="keyword" placeholder="货号或名称" size="7" name="keyword">
    </div>
    <button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-search"></span></button>
</form>
<table class="table">
    <tr>
        <th>id</th>
        <th>名称</th>
        <th>货号</th>
        <th>logo</th>
        <th>商品分类</th>
        <th>品牌</th>
        <th>市场价格</th>
        <th>本店价格</th>
        <th>库存</th>
        <th>是否上架</th>
        <th>排序</th>
        <th>录入时间</th>
        <th>操作</th>
    </tr>
    <?php foreach ($articles as $good):?>
        <tr>
            <td><?=$good->id?></td>
            <td><?=$good->name?></td>
            <td><?=$good->sn?></td>
            <td><?php
                $imgPath=strpos($good->logo,'ttp://')?$good->logo:"/".$good->logo;
                echo \yii\bootstrap\Html::img($imgPath,['height'=>30]);
                ?>
            </td>
            <td><?=$good->brand->name?></td>
            <td><?=$good->cate->name?></td>
            <td><?=$good->market_price?></td>
            <td><?=$good->shop_price?></td>
            <td><?=$good->sort?></td>
            <td><?=$good->stock?></td>
            <td><?=\backend\models\Goods::$is[$good->status]?></td>
            <td><?=date("Y-m-d H:i:s",$good->create_time)?></td>
            <td>

                <a href="<?=yii\helpers\Url::to(['goods/edit','id'=>$good->id])?>" class="btn btn-success">编辑</a>
                <a href="<?=yii\helpers\Url::to(['goods/del','id'=>$good->id])?>" class="btn btn-danger">删除</a>

            </td>
        </tr>
    <?php endforeach; ?>
</table>
<?=
\yii\widgets\LinkPager::widget([
    "pagination" => $page
])
?>

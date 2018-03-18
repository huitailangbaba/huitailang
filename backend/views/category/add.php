<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018\3\18 0018
 * Time: 16:45
 */
/** @var $this \yii\web\View */
$form=\yii\bootstrap\ActiveForm::begin();

echo $form->field($category,"name");
echo $form->field($category,"parent_id")->textInput(["value"=>0]);
echo \liyuze\ztree\ZTree::widget([
    'setting' => '{
			data: {
				simpleData: {
					enable: true,
					pIdKey: "parent_id",
				}
			},
			callback: {
				onClick: onClick
				}
		}',
    'nodes' => $cate
]);
echo $form->field($category,"intro")->textarea();

echo \yii\bootstrap\Html::submitButton("提交",["class"=>"btn btn-info"]);


\yii\bootstrap\ActiveForm::end();
?>
<script>
    function onClick(e,treeId, treeNode) {
        //找到父id的位置
        $("#category-parent_id").val(treeNode.id);
        console.dir(treeNode.id)
    }
</script>
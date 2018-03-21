<?php
$form = \yii\bootstrap\ActiveForm::begin();

echo $form->field($good,"name");
echo $form->field($good,"sn");
echo $form->field($good,"category_id")->dropDownList($cateArr,["prompt"=>"亲。选择一下"]);
echo $form->field($good,"brand_id")->dropDownList($brandArr,["prompt"=>"亲。选择一下"]);
echo $form->field($good,"market_price");
echo $form->field($good,"shop_price");
echo $form->field($good,"sort")->textInput(["value"=>"100"]);
echo $form->field($good,"stock");
echo $form->field($good,"status")->inline()->radioList(\backend\models\Brand::$is,["value"=>"1"]);
echo $form->field($good,"logo")->widget(\manks\FileInput::className(),[]);
echo $form->field($good, 'images')->widget(\manks\FileInput::className(), [
    'clientOptions' => [
        'pick' => [
            'multiple' => true,
        ],
        // 'server' => Url::to('upload/u2'),
        // 'accept' => [
        // 	'extensions' => 'png',
        // ],
    ],
]);
echo $form->field($goods_cont,'content')->widget('kucha\ueditor\UEditor',[]);


echo \yii\bootstrap\Html::submitButton("提交",["class"=>"btn btn-info"]);

\yii\bootstrap\ActiveForm::end();
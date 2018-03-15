<?php
$form = \yii\bootstrap\ActiveForm::begin();

echo $form->field($brand,"name");
echo $form->field($brand,"status")->inline()->radioList(\backend\models\Brand::$is,["value"=>"1"]);
echo $form->field($brand,"sort");
echo $form->field($brand,"intro")->textarea();
echo $form->field($brand,"img")->fileInput();

echo \yii\bootstrap\Html::submitButton("提交",["class"=>"btn btn-info"]);

\yii\bootstrap\ActiveForm::end();
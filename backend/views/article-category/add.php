<?php
$form = \yii\bootstrap\ActiveForm::begin();

echo $form->field($article,"name");
echo $form->field($article,"sort");
echo $form->field($article,"status")->inline()->radioList(\backend\models\Brand::$is,["value"=>"1"]);
echo $form->field($article,"is_help")->inline()->radioList(\backend\models\ArticleCategory::$as,["value"=>"1"]);
echo $form->field($article,"intro")->textarea();


echo \yii\bootstrap\Html::submitButton("提交",["class"=>"btn btn-info"]);

\yii\bootstrap\ActiveForm::end();
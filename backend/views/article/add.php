<?php
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($article,"name");
echo $form->field($article,"sort")->textInput(["value"=>"100"]);
echo $form->field($article,"create_id")->dropDownList($cate);
echo $form->field($article,"status")->inline()->radioList(\backend\models\Article::$is,["value"=>"1"]);
echo $form->field($article,"intro")->textarea();
echo $form->field($model,'content')->widget('kucha\ueditor\UEditor',[]);
echo \yii\bootstrap\Html::submitButton("提交",["class"=>"btn btn-info"]);

\yii\bootstrap\ActiveForm::end();
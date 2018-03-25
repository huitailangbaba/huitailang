<?php
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,"user_id")->dropDownList($admin);
echo $form->field($model,"item_name")->inline()->radioList($role);
echo \yii\bootstrap\Html::submitButton("提交",["class"=>"btn btn-info"]);

\yii\bootstrap\ActiveForm::end();
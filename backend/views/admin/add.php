<?php
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,"name");
echo $form->field($model,"password")->passwordInput(["value"=>""]);
echo $form->field($model,"email");
echo $form->field($model,"status")->inline()->radioList(["1"=>"上线","2"=>"下线"],["value"=>1]);
echo $form->field($model,"img")->widget(\manks\FileInput::className(),[]);
echo \yii\bootstrap\Html::submitButton("提交",["class"=>"btn btn-info"]);

\yii\bootstrap\ActiveForm::end();
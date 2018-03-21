<?php

namespace backend\controllers;

use backend\models\Category;
use yii\data\ActiveDataProvider;
use yii\data\Pagination;
use yii\helpers\Json;

class CategoryController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $category = Category::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $category,
            'pagination' => false
        ]);

        return $this->render('index',compact("dataProvider"));
    }


    //添加方法
    public function actionAdd(){
        $category = new  Category();

        //查出所有的分类
        $cates = Category::find()->asArray()->all();
        $cates[]=["name"=>"一级分类","parent_id"=>0,"id"=>0];
        $cate = json_encode($cates);

        $request = \Yii::$app->request;
        if($request->isPost){
            //数据绑定
            $category->load($request->post());
         if($category->validate()){
             //添加以及分类
              if($category->parent_id==0){
                       $category->makeroot();
                       \Yii::$app->session->setFlash("success","创建一级分类".$category->name."成功");
                       //刷新
                       return $this->refresh();
              }else{
                  //找到父亲分类
                  $cateParent = Category::findOne($category->parent_id);
                  //把新的分类加入到父类中去
                  $category->prependTo($cateParent);
                  \Yii::$app->session->setFlash("success","创建".$cateParent->name."的子分类".$category->name."成功");
                  //刷新
                  return $this->refresh();
              }


         }

        }
        return $this->render("add",compact("category","cate"));
    }
    //修改方法
    public function actionUpdate($id){
       $row= Category::$row;
        $category = Category::findOne($id);
        //查出所有的分类
        $cates = Category::find()->asArray()->all();
        foreach ($cates as $data){
            if ($id!=$data["parent_id"] && $id!=$data["id"]){
               $row[]=$data;
            }
        }
        $row[]=["name"=>"一级分类","parent_id"=>0,"id"=>0];
        $cate = json_encode($row);

        $request = \Yii::$app->request;
        if($request->isPost){
            //数据绑定
            $category->load($request->post());
            if($category->validate()){
                //添加以及分类
                if($category->parent_id==0){
                    $category->save();
                    \Yii::$app->session->setFlash("success","创建一级分类".$category->name."成功");
                    //刷新
                    return $this->refresh();
                }else{
                    //找到父亲分类
                    $cateParent = Category::findOne($category->parent_id);
                    //把新的分类加入到父类中去
                    $category->prependTo($cateParent);
                    \Yii::$app->session->setFlash("success","修改".$cateParent->name."的子分类".$category->name."成功");
                    //刷新
                    return $this->refresh();
                }


            }

        }
        return $this->render("add",compact("category","cate"));
    }
    public function actionDelete($id){
        //查出所有的分类
        $cates = Category::find()->where(["parent_id"=>$id])->all();
        if($cates==null){
            Category::findOne($id)->delete();
            //删除成功返回首页
            \Yii::$app->session->setFlash("success","删除成功");
            return $this->redirect(["index"]);
        }else{
            \Yii::$app->session->setFlash("success","不能删除，有子节点");
            return $this->redirect(["index"]);
        }
    }
}

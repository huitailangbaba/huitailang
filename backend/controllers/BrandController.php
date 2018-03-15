<?php

namespace backend\controllers;

use backend\models\Brand;
use yii\data\Pagination;
use yii\web\UploadedFile;

class BrandController extends \yii\web\Controller
{
    //显示方法
    public function actionIndex()
    {
        //读出所有数据
        $brand = Brand::find()->where(["status"=>"1"]);
        //得到总的条数
        $count = $brand->count();

        //使用总数来创建一个分页对象
        $page = new Pagination([
            //每一页显示的条数
            "pageSize" => "3",
            //总条数
            "totalCount" => $count,
        ]);
        $brands = $brand->offset($page->offset)->limit($page->limit)->all();
        return $this->render('index',compact("brands","page"));
    }

    //添加方法
    public function actionAdd(){
        //创建数据模型
        $brand = new  Brand();
        //定义一个request
        $request = \Yii::$app->request;

        //判断是否是post提交
        if($request->isPost){

            //得到上传的图片对象
            $brand->img = UploadedFile::getInstance($brand,"img");

            $imgPath="";

            if($brand->img!==null){
                //设定一个临时的图片存放路径
                $imgPath="images/".time().".".$brand->img->extension;

                //移动图片路劲

                $brand->img->saveAs($imgPath);

            }
                //绑定数据
                $brand->load($request->post());
                //后端验证
                if($brand->validate()){
                    //验证成功   保存数据
                    $brand->logo= $imgPath;
                    if ($brand->save()) {
                        //添加成功 调回首页
                        \Yii::$app->session->setFlash("success","添加修改");
                        return $this->redirect(["index"]);
                    }
                }else{
                    //打印错误
                    var_dump($brand->errors);exit;
                }


        }


        return $this->render("add",compact("brand"));
    }




    //修改方法
    public function actionEdit($id){

          $brand= Brand::findOne($id);

        //定义一个request
        $request = \Yii::$app->request;

        //判断是否是post提交
        if($request->isPost){

            //得到上传的图片对象
            $brand->img = UploadedFile::getInstance($brand,"img");

            if($brand->img!==null) {
                //设定一个临时的图片存放路径
                $imgPath = "images/" . time() . "." . $brand->img->extension;

                //移动图片路劲
                $brand->img->saveAs($imgPath);
                $brand->logo= $imgPath;
            }

                //绑定数据
                $brand->load($request->post());
                //后端验证
                if($brand->validate()){
                    //验证成功   保存数据

                    if ($brand->save()) {
                        //添加成功 调回首页
                        \Yii::$app->session->setFlash("success","修改成功");
                        return $this->redirect(["index"]);
                    }
                }else{
                    //打印错误
                    var_dump($brand->errors);exit;
                }


        }


        return $this->render("add",compact("brand"));
    }

    //删除方法
    public function actionDel($id){
         Brand::updateAll(["status"=>2],["id"=>$id]);
        \Yii::$app->session->setFlash("success","删除成功");
        return $this->redirect(["index"]);
    }

    //回收站方法
    public function actionH(){
        //读出所有数据
        $brand = Brand::find()->where(["status"=>"2"]);
        //得到总的条数
        $count = $brand->count();

        //使用总数来创建一个分页对象
        $page = new Pagination([
            //每一页显示的条数
            "pageSize" => "3",
            //总条数
            "totalCount" => $count,
        ]);
        $brands = $brand->offset($page->offset)->limit($page->limit)->all();
        return $this->render("H",compact("brands","page"));
    }
    //还原方法
    public function actionF($id){
        Brand::updateAll(["status"=>1],["id"=>$id]);
        \Yii::$app->session->setFlash("success","还原成功");
        return $this->redirect(["h"]);
    }
}

<?php

namespace backend\controllers;

use backend\models\Brand;
use backend\models\Category;
use backend\models\Goods;
use backend\models\GoodsCont;
use backend\models\GoodsPath;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;

class GoodsController extends \yii\web\Controller
{
    public function actionIndex()
    {

        // 创建一个 DB 查询来获得所有 status 为 1 的文章
        $goods = Goods::find();

         //得到总的条数
        $count = $goods->count();

        $min = \Yii::$app->request->get("minshop");
        $max = \Yii::$app->request->get("maxshop");
        $key = \Yii::$app->request->get("keyword");
        $status = \Yii::$app->request->get("status");

        if($min){
            $goods->andwhere("shop_price>={$min}");
        }
        if($max){
            $goods->andwhere("shop_price<={$max}");
        }
        if($key!==""){
            $goods->andWhere("name like '%{$key}%' or sn like '%{key}%'" );
        }
        if($status =="2" || $status=="1"){
            $goods->andWhere(["status"=>$status]);
        }
        //使用总数来创建一个分页对象
        $page = new Pagination([
            //每一页显示的条数
            "pageSize" => "3",
            //总条数
            "totalCount" => $count,
        ]);
        $articles = $goods->offset($page->offset)->limit($page->limit)->all();



        return $this->render('index',compact("goods","articles","page"));



    }
   //添加模块
    public function actionAdd(){
        $goods_path = new  GoodsPath();
        $goods_cont = new  GoodsCont();
        $good = new Goods();
        //取出category所有深度为3的商品
       $category = Category::find()->orderBy("tree,lft")->all();
       //转换为1为数组
        $cateArr =ArrayHelper::map($category,"id","nametext");
        //读出brand的所有数据
        $brand = Brand::find()->all();
        $brandArr = ArrayHelper::map($brand,"id","name");

        $request = \Yii::$app->request;
        //判断是否是post提交
        if($request->isPost){
          //绑定数据
            $good->load($request->post());
            $goods_cont->load($request->post());
            $goods_path->load($request->post());

            //判断sn是否为空，如果为空就给它重新赋值
            if($good->sn==""){
                //获取今天的时间
                $data =date("Ymd");
                $content=strtotime(date("Ymd"));
                //找出今天总的商品数
                $cont = Goods::find()->where([">","create_time",$content])->count();
                $cont+=1;
                $contStr = "0000".$cont;
                $contStr=substr($contStr,"-5");
                //拼接sn
                $good->sn = $data.$contStr;
            }

            if($good->validate()){
                //保存数据
                if ($good->save()) {
                    //循环图片 保存
                    foreach ($good->images as $img){
                        $images = new GoodsPath();
                        $images->goods_id = $good->id;
                        $images->path = $img;
                        $images->save();
                    }
                     $goods_cont->goods_id=$good->id;

                     if($goods_cont->save()){
                         //添加成功 调回首页
                         \Yii::$app->session->setFlash("success","添加修改");
                         return $this->redirect(["index"]);
                     }

                    }

            }

        }


        return $this->render("add",compact("good","cateArr","brandArr","goods_cont"));
    }


    //修改模块
    public function actionEdit($id){
        $goods_path = GoodsPath::findOne(["goods_id"=>$id]);
        $goods_cont =GoodsCont::findOne(["goods_id"=>$id]);
        $good = Goods::findOne($id);
        //取出category所有深度为3的商品
        $category = Category::find()->orderBy("tree,lft")->all();
        //转换为1为数组
        $cateArr =ArrayHelper::map($category,"id","nametext");
        //读出brand的所有数据
        $brand = Brand::find()->all();
        $brandArr = ArrayHelper::map($brand,"id","name");

        $request = \Yii::$app->request;
        //判断是否是post提交
        if($request->isPost){
            //绑定数据
            $good->load($request->post());
            $goods_cont->load($request->post());
            $goods_path->load($request->post());

            //判断sn是否为空，如果为空就给它重新赋值
            if($good->sn==""){
                //获取今天的时间
                $data =date("Ymd");
                $content=strtotime(date("Ymd"));
                //找出今天总的商品数
                $cont = Goods::find()->where([">","create_time",$content])->count();
                $cont+=1;
                $contStr = "0000".$cont;
                $contStr=substr($contStr,"-5");
                //拼接sn
                $good->sn = $data.$contStr;
            }

            if($good->validate()){
                //保存数据
                if ($good->save()) {
                    //循环图片 保存
                    foreach ($good->images as $img){
                        $images = new GoodsPath();
                        $images->goods_id = $good->id;
                        $images->path = $img;
                        $images->save();
                    }
                    $goods_cont->goods_id=$good->id;

                    if($goods_cont->save()){
                        //添加成功 调回首页
                        \Yii::$app->session->setFlash("success","添加修改");
                        return $this->redirect(["index"]);
                    }

                }

            }

        }
        $images = GoodsPath::find()->where(["goods_id"=>$id])->asArray()->all();
        $images = array_column($images,"path");

        $good->images=$images;

        return $this->render("add",compact("good","cateArr","brandArr","goods_cont"));
    }


    public function actions()
    {
        return [
            'upload' => [
                'class' => 'kucha\ueditor\UEditorAction',
                'config' => [
                    "imageUrlPrefix"  => "http://admin.yii.com",//图片访问路径前缀
            ],
            ]
        ];
    }
   // 删除模块
    public function actionDel($id){
        if (Goods::findOne($id)->delete()) {
            if (GoodsCont::findOne(["goods_id"=>$id])->delete()) {
                if (GoodsPath::find(["goods_id"=>$id])->all()) {
                    //删除成功 调回首页
                    \Yii::$app->session->setFlash("success","删除修改");
                    return $this->redirect(["index"]);
                }
            }
        }
    }
}

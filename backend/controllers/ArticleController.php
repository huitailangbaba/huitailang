<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018\3\16 0016
 * Time: 19:39
 */

namespace backend\controllers;


use backend\models\Article;
use backend\models\ArticleCategory;
use backend\models\ArticleData;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;
use yii\web\Controller;

class ArticleController extends Controller
{
     public function actionIndex(){
         //读出所有数据
         $article = Article::find();
         //得到总的条数
         $count = $article->count();

         //使用总数来创建一个分页对象
         $page = new Pagination([
             //每一页显示的条数
             "pageSize" => "3",
             //总条数
             "totalCount" => $count,
         ]);
         $articles = $article->offset($page->offset)->limit($page->limit)->all();
         return $this->render('index',compact("articles","page"));
     }
     //添加方法
     public function actionAdd(){
          $article  = new Article();
          $model = new ArticleData();
         //得到图书分类内容
          $data = ArticleCategory::find()->all();
          $cate = ArrayHelper::map($data,"id","name");

       $request = \Yii::$app->request;
       //判断是否是post提交
         if($request->isPost){
             //绑定数据
             $article->load($request->post());
             //后台验证
             if($article->validate()){
                 //保存数据
                 if($article->save()){
                     $model->load($request->post());
                     //取出article的id保存到article_data里
                     $model->article_id=$article->id;
                     //保存数据
                     if ($model->save()) {
                         //添加成功 调回首页
                         \Yii::$app->session->setFlash("success","添加修改");
                         return $this->redirect(["index"]);
                     }
                 }
             }
         }



          return $this->render("add",compact("article","model","cate"));
     }
     //修改方法
    public function actionEdit($id){
        $article  = Article::findOne($id);
        $model = ArticleData::findOne($id);
        //得到图书分类内容
        $data = ArticleCategory::find()->all();
        $cate = ArrayHelper::map($data,"id","name");

        $request = \Yii::$app->request;
        //判断是否是post提交
        if($request->isPost){
            //绑定数据
            $article->load($request->post());
            //后台验证
            if($article->validate()){
                //保存数据
                if($article->save()){
                    $model->load($request->post());
                    //保存数据
                    if ($model->save()) {
                        //添加成功 调回首页
                        \Yii::$app->session->setFlash("success","添加修改");
                        return $this->redirect(["index"]);
                    }
                }
            }
        }



        return $this->render("add",compact("article","model","cate"));
    }
    public function actionDel($id){
        Article::findOne($id)->delete();
        $model = ArticleData::findOne($id)->delete();
        if($model){
            //添加成功 调回首页
            \Yii::$app->session->setFlash("success","修改修改");
            return $this->redirect(["index"]);
        }
    }
    public function actions()
    {
        return [
            'upload' => [
                'class' => 'kucha\ueditor\UEditorAction',
            ]
        ];
    }
}
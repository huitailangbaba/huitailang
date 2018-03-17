<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018\3\16 0016
 * Time: 14:19
 */

namespace backend\controllers;


use backend\models\ArticleCategory;
use yii\data\Pagination;
use yii\web\Controller;

class ArticleCategoryController extends Controller
{
    //显示方法
    public function actionIndex()
    {
        //读出所有数据
        $article = ArticleCategory::find();
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
        //创建数据模型
        $article = new ArticleCategory();
        //定义一个request
        $request = \Yii::$app->request;

        //判断是否是post提交
        if($request->isPost){

            //绑定数据
            $article->load($request->post());
            //后端验证
            if($article->validate()){
                //验证成功   保存数据
                if ($article->save()) {
                    //添加成功 调回首页
                    \Yii::$app->session->setFlash("success","添加修改");
                    return $this->redirect(["index"]);
                }
            }

        }


        return $this->render("add",compact("article"));
    }
    //修改方法
    public function actionEdit($id){
       $article = ArticleCategory::findOne($id);
        //定义一个request
        $request = \Yii::$app->request;

        //判断是否是post提交
        if($request->isPost){

            //绑定数据
            $article->load($request->post());
            //后端验证
            if($article->validate()){
                //验证成功   保存数据
                if ($article->save()) {
                    //添加成功 调回首页
                    \Yii::$app->session->setFlash("success","添加修改");
                    return $this->redirect(["index"]);
                }

            }
        }
        return $this->render("add",compact("article"));
    }
    //删除方法
    public function actionDel($id){
        if($article = ArticleCategory::findOne($id)->delete()){
            //删除成功返回首页
            \Yii::$app->session->setFlash("success","删除成功");
            return $this->redirect(["index"]);
        }
    }
}
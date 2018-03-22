<aside class="main-sidebar">

    <section class="sidebar">
<br/>
       

        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="<?= Yii::$app->user->identity->img?>" class="img-circle" alt="User Image"/>
            </div>
            <div class="pull-left info">
                <p><?=Yii::$app->user->identity->name?></p>

                <a href="#"><i class="fa fa-circle text-success"></i>英俊的boy</a>
            </div>
        </div>
<br/>
        <br/>
        <!-- search form -->
        <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Search..."/>
              <span class="input-group-btn">
                <button type='submit' name='search' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
            </div>
        </form>
        <!-- /.search form -->

        <?= dmstr\widgets\Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu tree', 'data-widget'=> 'tree'],
                'items' => [
                    ['label' => 'Gii', 'icon' => 'file-code-o', 'url' => ['/gii']],


                    [
                        'label' => '商品管理',
                        'icon' => 'share',
                        'url' => '#',
                        'items' => [
                            ['label' => '商品首页', 'icon' => 'file-code-o', 'url' => ['goods/index'],],
                            ['label' => '商品添加', 'icon' => 'file-code-o', 'url' => ['goods/add'],],
                        ],
                    ],



                    [
                        'label' => '品牌管理',
                        'icon' => 'share',
                        'url' => '#',
                        'items' => [
                            ['label' => '品牌首页', 'icon' => 'file-code-o', 'url' => ['brand/index'],],
                            ['label' => '品牌添加', 'icon' => 'file-code-o', 'url' => ['brand/add'],],
                            ['label' => '回收站', 'icon' => 'file-code-o', 'url' => ['brand/h'],],
                        ],
                    ],

                    [
                        'label' => '文章管理',
                        'icon' => 'share',
                        'url' => '#',
                        'items' => [
                            ['label' => '文章首页', 'icon' => 'file-code-o', 'url' => ['article/index'],],
                            ['label' => '文章添加', 'icon' => 'file-code-o', 'url' => ['article/add'],],
                            ['label' => '文章分类管理', 'icon' => 'file-code-o', 'url' => ['article-category/index'],
                                'items' => [
                                    ['label' => '文章分类首页', 'icon' => 'file-code-o', 'url' => ['article-category/index'],],
                                    ['label' => '文章分类添加', 'icon' => 'file-code-o', 'url' => ['article-category/add'],],
                                ],
                                ],
                        ],
                    ],


                    [
                        'label' => '管理员管理',
                        'icon' => 'share',
                        'url' => '#',
                        'items' => [
                            ['label' => '管理员首页', 'icon' => 'file-code-o', 'url' => ['admin/index'],],
                            ['label' => '管理员添加', 'icon' => 'file-code-o', 'url' => ['admin/add'],],
                        ],
                    ],






                ],
            ]
        ) ?>

    </section>

</aside>

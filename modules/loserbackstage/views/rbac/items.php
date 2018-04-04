<?php
    use yii\grid\GridView;
    use yii\helpers\Html;
    $this->title = '角色列表';
    $this->params['breadcrumbs'][] = ['label' => '角色管理', 'url' => ['/admin/rbac/roles']];
    $this->params['breadcrumbs'][] = $this->title;
    $this->registerCssFile('admin/css/compiled/user-list.css');
?>
        
        <div class="container-fluid">
            <div id="pad-wrapper" class="users-list">
                <div class="row-fluid header">
                    <h3>角色列表</h3>
                </div>
                <?php
                    echo GridView::widget([
                        'dataProvider' => $dataProvider,
                        'columns' => [
                            [
                                'class' => 'yii\grid\SerialColumn',
                                'header'=>'序号'
                            ],
                            [
                                    'label'=>'名称',
                                    'attribute' => 'description',
                            ],
                            [
                                    'label'=>'标识',
                                    'attribute' => 'name',
                            ],
                            [
                                    'label'=>'规则名称',
                                    'attribute' => 'rule_name',
                            ],
                            [
                                    'label'=>'创建日期',
                                    'attribute' => 'created_at',
                                    'value'=>function($model){
                                            return  date('Y-m-d H:i:s',$model['created_at']);   
                                    },
                            ],
                            [
                                    'label'=>'更新时间',
                                    'attribute' => 'updated_at',
                                    'value'=>function($model){
                                            return  date('Y-m-d H:i:s',$model['updated_at']);   
                                    },
                            ],
                            [
                                'class' => 'yii\grid\ActionColumn',
                                'header' => '操作',
                                'template' => '{assign} {update} {delete}',
                                'buttons' => [
                                    //$model 是循环二维数组中一次的数据
                                    'assign' => function ($url, $model, $key) {
                                        return Html::a('分配权限', ['assignitem', 'name' => $model['name']]);
                                    },
                                    'update' => function ($url, $model, $key) {
                                        return Html::a('更新', ['updateitem', 'name' => $model['name']]);
                                    },
                                    'delete' => function ($url, $model, $key) {
                                        return Html::a('删除', ['deleteitem', 'name' => $model['name']]);
                                    }
                                ],
                            ],
                        ],
                        //调整样式 把分页调到右侧
                        'layout' => "\n{items}\n{summary}<div class='pagination pull-right'>{pager}</div>",
                        'pager'=>[
                            // 'options'=>['class'=>'hidden']//关闭分页
                            'firstPageLabel'=>"首页",
                            'prevPageLabel'=>'Prev',
                            'nextPageLabel'=>'Next',
                            'lastPageLabel'=>'尾页',
                         ]
                    ]);

                ?>

            </div>
        </div>
    <!-- end main container -->

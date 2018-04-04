<?php
$this->title = '分类列表';
$this->params['breadcrumbs'][] = "->";
$this->params['breadcrumbs'][] = ['label' => '分类管理管理', 'url' => ['category/list']];
$this->params['breadcrumbs'][] = $this->title;
$this->registerCssFile('/loserbackstage/css/compiled/user-list.css');
?>
    
        
        <div class="container-fluid">
            <div id="pad-wrapper" class="users-list">
                <div class="row-fluid header">
                    <h3>分类列表</h3>
                    <div class="span10 pull-right">
                        <a href="<?php echo yii\helpers\Url::to(['category/add']) ?>" class="btn-flat success pull-right">
                            <span>&#43;</span>
                            添加新分类
                        </a>
                    </div>
                </div>
                    <div class="container-fluid">
            <div id="pad-wrapper" class="users-list">
                    <div class="row-fluid header">
                       <?= \yiidreamteam\jstree\JsTree::widget([
                            'containerOptions' => [
                                'class' => 'data-tree',
                            ],
                            'jsOptions' => [
                                'core' => [
                                    'check_callback'=>true,
                                    'multiple' => false,
                                    // [{"id" : 1, "text" : "服装", "children" : [{}, {}]}, {}]
                                    'data' => [
                                        'url' => \yii\helpers\Url::to(['category/tree', "page" => $page, "perpage" => $perpage]),
                                    ],
                                ],
                                "plugins" => [
                                'contextmenu', 'dnd', 'search', 'state', 'types', 'wholerow'
                                ],
                            ]
                        ]) ?>
                    </div>
                </div>
            </div>
            <div class="pagination pull-right">
                    <?php echo yii\widgets\LinkPager::widget([
                        'pagination' => $pager,
                        'prevPageLabel' => '&#8249;',
                        'nextPageLabel' => '&#8250;',
                        ]); ?>
                </div>
                
                <!-- end users table -->
            </div>
        </div>
    
    <!-- end main container -->

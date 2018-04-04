<?php
    use yii\bootstrap\ActiveForm;
    use yii\helpers\Html;
?>
<style>
    .span8 div{
        display:inline;
    }
    .help-block-error {
        color:red;
        display:inline;
    }
</style>
    <link rel="stylesheet" href="/css/compiled/new-user.css" type="text/css" media="screen" />
    <!-- main container -->
    <div class="content">
        <div class="container-fluid">
            <div id="pad-wrapper" class="new-user">
                <div class="row-fluid header">
                    <h3>导入表中数据到商城中</h3>
                </div>
                <div class="row-fluid form-wrapper">
                    <!-- left column -->
                    <div class="span9 with-sidebar">
                        <div class="container">
                                <?php
                                if (Yii::$app->session->hasFlash('info')) {
                                    echo Yii::$app->session->getFlash('info');
                                }
                                $form = ActiveForm::begin([
                                    'fieldConfig' => [
                                        'template' => '<div class="span12 field-box">{label}{input}</div>{error}',
                                    ],
                                    'options' => [
                                        'class' => 'new_user_form inline-input',
                                        'enctype' => 'multipart/form-data'
                                    ],
                                ]);
                                echo $form->field($model, 'xsl')->fileInput(['class' => 'span9']);
                                ?>
                                <div class="span11 field-box actions">
                                    <?php echo Html::submitButton('提交', ['class' => 'btn-glow primary']); ?>
                                    <span>OR</span>
                                    <?php echo Html::resetButton('取消', ['class' => 'reset']); ?>
                                </div>
                            <?php ActiveForm::end(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end main container -->

<?php
    use yii\bootstrap\ActiveForm;
    use yii\helpers\Html;
    
$this->title = '管理员添加';
$this->params['breadcrumbs'][] = "->";
$this->params['breadcrumbs'][] = ['label' => '管理员管理', 'url' => ['manage/managers']];
$this->params['breadcrumbs'][] = $this->title;

?>
  
        <div class="container-fluid">
            <div id="pad-wrapper" class="new-user">
                <div class="row-fluid header">
                    <h3>添加管理员</h3>
                </div>

                <div class="row-fluid form-wrapper">
                    <!-- left column -->
                    <div class="span9 with-sidebar">
                            <?php 
                            $form = ActiveForm::begin([
                                'options' => ['class' => 'new_user_form inline-input'],
                                'fieldConfig' => [
                                    'template' => '<div class="span12 field-box">{label}{input}</div>{error}'
                                ],
                            ]);
                            ?>
                            <?php if(Yii::$app->session->hasFlash('info')){
                                echo Yii::$app->session->getFlash('info');
                            }?>
                            <?php echo $form->field($model, 'adminuser')->textInput(['class' => 'span9']); ?>
                            <?php echo $form->field($model, 'adminemail')->textInput(['class' => 'span9']); ?>
                            <?php echo $form->field($model, 'adminpass')->passwordInput(['class' => 'span9']); ?>
                            <?php echo $form->field($model, 'repass')->passwordInput(['class' => 'span9']); ?>
                        <div class="span11 field-box actions">
                            <?php echo Html::submitButton('创建', ['class' => 'btn-glow primary']); ?>
                            <span>或者</span>
                            <?php echo Html::resetButton('取消', ['class' => 'reset']); ?>
                        </div>
                        <?php ActiveForm::end();?>
                    </div>

                   
                </div>
            </div>
        </div>
 
    <!-- end main container -->


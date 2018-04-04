<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
?>
<!DOCTYPE html>
<html class="login-bg">
<head>
    <title>慕课商城 - 找回密码</title>
    
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    
    <!-- bootstrap -->
    <link href="/css/bootstrap/bootstrap.css" rel="stylesheet" />
    <link href="/css/bootstrap/bootstrap-responsive.css" rel="stylesheet" />
    <link href="/css/bootstrap/bootstrap-overrides.css" type="text/css" rel="stylesheet" />

    <!-- global styles -->
    <link rel="stylesheet" type="text/css" href="/css/layout.css" />
    <link rel="stylesheet" type="text/css" href="/css/elements.css" />
    <link rel="stylesheet" type="text/css" href="/css/icons.css" />

    <!-- libraries -->
    <link rel="stylesheet" type="text/css" href="/css/lib/font-awesome.css" />
    
    <!-- this page specific styles -->
    <link rel="stylesheet" href="/css/compiled/signin.css" type="text/css" media="screen" />

    <!-- open sans font -->
    <!-- <link href='http://fonts.useso.com/css?family=Open+Sans:300italic,400italic,700italic,800italic,400,300,600,700,800' rel='stylesheet' type='text/css' /> -->

    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /></head>
<body>


    <div class="row-fluid login-wrapper">
        <a class="brand" href="index.html"></a>
    <?php $form=ActiveForm::begin([
        //对所有的input框去掉多余标签
        'fieldConfig'=>[
            'template' => '{input}{error}',
        ],
        ]);?>
        <div class="span4 box">
            <div class="content-wrap">
                <h6>慕课商城 - 修改密码</h6>
                <?php 
                if(Yii::$app->session->getFlash('info')){
                    echo Yii::$app->session->getFlash('info');
                }
                ?>
                <?php echo $form->field($model, 'adminuser')->hiddenInput(); ?>
                <?php echo $form->field($model, 'adminpass')->passwordInput(["class" => "span12", "placeholder" => "新密码"]); ?>
                <?php echo $form->field($model, 'repass')->passwordInput(["class" => "span12", "placeholder" => "确认密码"]); ?>
                <a href="<?php echo yii\helpers\Url::to(['public/login'])?>" class="forgot">返回登录</a>
                <?php echo HTML::submitButton('修改',["class"=>"btn-glow primary login"])?>

            </div>
        </div>
    <?php $form=ActiveForm::end();?>
    </div>

    <!-- scripts -->
    <script src="/js/jquery-latest.js"></script>
    <script src="/js/bootstrap.min.js"></script>
    <script src="/js/theme.js"></script>

    <!-- pre load bg imgs -->
    <script type="text/javascript">
        $(function () {
            // bg switcher
            var $btns = $(".bg-switch .bg");
            $btns.click(function (e) {
                e.preventDefault();
                $btns.removeClass("active");
                $(this).addClass("active");
                var bg = $(this).data("img");

                $("html").css("background-image", "url('img/bgs/" + bg + "')");
            });

        });
    </script>

</body>
</html>
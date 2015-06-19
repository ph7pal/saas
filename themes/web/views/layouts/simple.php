<!DOCTYPE html>
<html>  
    <head>    
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no">
        <meta name="viewport" content="initial-scale=1.0,user-scalable=no,maximum-scale=1">
        <meta content="yes" name="apple-mobile-web-app-capable" />
        <meta content="black" name="apple-mobile-web-app-status-bar-style"  />
        <meta name="apple-touch-fullscreen" content="yes">
        <meta name="full-screen" content="yes">
        <meta name="format-detection" content="telephone=no">    
        <meta name="format-detection" content="address=no">
        <?php
        $cs = Yii::app()->clientScript;
        $cs->registerCssFile(Yii::app()->baseUrl . '/common/css/bootstrap.min.css');
        $cs->registerCssFile(Yii::app()->baseUrl . '/common/css/zmf.css');
        $cs->registerCoreScript('jquery');
        $cs->registerScriptFile(Yii::app()->baseUrl . "/common/js/bootstrap.min.js", CClientScript::POS_HEAD);
        $cs->registerScriptFile(Yii::app()->baseUrl . "/common/js/zmf.js", CClientScript::POS_END);
        ?>  
        <title><?php echo CHtml::encode($this->pageTitle); ?></title>
    </head>
    <body>
        <div class="container">                      
            <?php echo $content; ?>
        </div>
    </body>
</html>
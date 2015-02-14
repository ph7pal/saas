<?php

return array(
    'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    'name' => '新灵旅行',
    'language' => 'zh_cn',
    'theme' => 'web',
    'preload' => array('log'),
    'onBeginRequest' => create_function('$event', 'return ob_start("ob_gzhandler");'),
    'onEndRequest' => create_function('$event', 'return ob_end_flush();'),
    'import' => array(
        'application.models.*',
        'application.components.*',
        'application.helpers.*'
    ),
    'modules' => array(
        'gii' => array(
            'class' => 'system.gii.GiiModule',
            'password' => '123456',
            // If removed, Gii defaults to localhost only. Edit carefully to taste.
            'ipFilters' => array(
                '127.0.0.1',
                '192.168.1.106',
                '::1'
            )
        ),
        'admin' => array(
            'class' => 'application.modules.admin.AdminModule',
            'defaultController' => 'index'
        )
    ),
    'defaultController' => 'index',
    'components' => array(
        'request' => array(
        // 'enableCsrfValidation'=>true, //防范跨站请求伪造(简称CSRF)攻击              
        //'enableCookieValidation'=>true,//对cookie的值进行HMAC检查
        ),
        'user' => array(
            'allowAutoLogin' => true,
        ),
        'db' => require(dirname(__FILE__) . '/db.php'),
        'errorHandler' => array(
            'errorAction' => 'error/index',
        ),
//        'image' => array(
//            'class' => 'application.extensions.image.CImageComponent',
//            'driver' => 'GD',
//            'params' => array(
//            )
//        ),
//        'urlManager' => require(dirname(__FILE__) . '/rewrite.php'),
        'filecache' => array(
            'class' => 'system.caching.CFileCache',
            'directoryLevel' => '2', //缓存文件的目录深度  
        ),
        'clientScript' => array(
            'scriptMap' => array(
                'pager.css' => false,
            ),
        ),
        'log' => array(
            'class' => 'CLogRouter',
            'routes' => array(
                array(
                    'class' => 'CFileLogRoute',
                    'levels' => 'error, warning',
                ),
            ),
        ),
	   'mailer' => array(
	      'class' => 'application.extensions.mailer.EMailer',
	      'pathViews' => 'application.views.email',
	      'pathLayouts' => 'application.views.email.layouts'
	   ),

    ),
    'params' => require(dirname(__FILE__) . '/params.php'),
);

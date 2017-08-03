<?php
namespace frontend\assets;
use yii\web\AssetBundle;
class LoginAsset extends AssetBundle{
    public $basePath='@webroot';//静态资源的硬盘路径
    public $baseUrl='@web';//静态资源的url路径
    //需要加载的css文件
    public $css=[
        'css/style/base.css',
        'css/style/global.css',
        'css/style/header.css',
        'css/style/login.css',
        'css/style/index.css',
        'css/style/bottomnav.css',
        'css/style/footer.css',
        'css/style/address.css',
        'css/style/home.css',
        'css/style/list.css',
        'css/style/common.css',
        'css/style/goods.css',
        'css/style/jqzoom.css',
        'css/style/cart.css',
        'css/style/fillin.css'
    ];
    //需要加载的js文件
    public $js=[
        'js/header.js',
        'js/index.js',
        'js/goods.js',
        'js/home.js',
        'js/list.js',
        'js/goods.js',
//        'js/jquery-1.8.3.min.js',
//        'js/jqzoom-core.js'
    ];
    //和其他静态资源的依赖关系
    public $depends=[
        'yii\web\JqueryAsset',
    ];
}
?>
<?php

namespace frontend\assets;

use yii\web\AssetBundle;

class AppAsset extends AssetBundle
{
	/**
	 * @inheritdoc
	 */
	public $basePath = '@webroot';

	/**
	 * @inheritdoc
	 */
	public $baseUrl = '@web';

	/**
	 * @inheritdoc
	 */
	public $css = [
		'css/fontello.css',
		'https://fonts.googleapis.com/css?family=Exo:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i%7cLato:100,100i,300,300i,400,400i,700,700i,900,900i',
		'css/style.css',
		'css/custom.css',
	];

	/**
	 * @inheritdoc
	 */
	public $js = [
		'js/main-app.js',
		'js/menumaker.js',
		'js/jquery.sticky.js',
		'js/sticky-header.js',
		'js/back-to-top.js',
		'js/timer.jquery.min.js',
	];

	/**
	 * @inheritdoc
	 */
	public $depends = [
		'yii\web\YiiAsset',
		'yii\bootstrap\BootstrapPluginAsset',
		'frontend\assets\NpmAsset',
		'frontend\assets\BowerAsset',
	];
}

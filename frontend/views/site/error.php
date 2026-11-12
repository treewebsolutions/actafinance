<?php
/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;
use tws\helpers\Url;

$this->title = Html::encode($message);
?>

<div class="space-small">
	<div class="container">
		<div class="error-block text-center">
			<img class="mb30" src="<?= Yii::getAlias('@web/img/error.png') ?>" alt="<?= Yii::$app->name ?>">
			<br>
			<a class="btn btn-lg btn-default btn-slide-up gap-t-lg" href="<?= Url::to(['/site/index']) ?>"><?= Yii::t('frontend', 'Go to Home Page') ?></a>
		</div>
	</div>
</div>

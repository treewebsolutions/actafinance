<?php
/* @var $this \yii\web\View */

use common\models\User;
use tws\widgets\typeahead\Typeahead;
use yii\helpers\Html;
use tws\helpers\Url;
use yii\web\JsExpression;

/** @var \common\models\User $user */
$user = Yii::$app->user->identity;
$appLanguages = \common\models\Language::findAllLanguages();
$appLanguagesCount = count($appLanguages);
$currentAppLanguage = $appLanguages[Yii::$app->language];
?>

<div id="page-header" class="header-wrapper">
	<div class="navigation-wrapper">
		<!-- header -->
		<div class="container">
			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<!-- navigation start-->
					<div id="navigation" class="navigation">
						<?php if ($topBarMenu = \common\models\Menu::findDefaultTopBarMenu()): ?>
							<?php
								$items = []; $subItems = [];
								$items[] = [
									'options' => [
										'class' => 'hidden-sm hidden-xs',
									],
									'label' => '<div class="logo" style="max-width: 160px; margin-right: 380px;">
											<img src="' . (Url::to('@uploads/' . Yii::$app->settings->get('appLogo')) && is_file(Yii::getAlias('@uploads/' . Yii::$app->settings->get('appLogo'))) ?: Url::to('@web/img/logo.png')) . '" alt="' . Yii::$app->name . '">
									</div>',
									'url' => ['/site/index'],
									'linkOptions' => [
										'style' => 'padding: 0; margin: 5px 0 0 0;',
									],
								];
								if (Yii::$app->user->isGuest) {
									$subItems[] = [
										'label' => Yii::t('common', 'Log In'),
										'url' => ['/site/login'],
									];
									$subItems[] = [
										'label' => Yii::t('common', 'Sign Up'),
										'url' => ['/site/signup'],
									];
								} else {
									$subItems[] = [
										'label' => Yii::t('common', 'Profile'),
										'url' => ['/account/profile/index'],
									];
									if ($user->status == User::STATUS_ACTIVE && $user->getHasPermissions()) {
										$subItems[] = [
											'label' => Yii::t('frontend', 'Admin Panel'),
											'url' => Url::to('/admin'),
											'linkOptions' => [
												'target' => '_blank',
											],
										];
									}
									$subItems[] = [
										'label' => Yii::t('common', 'Log Out'),
										'url' => ['/site/logout'],
										'linkOptions' => [
											'data' => [
												'confirm' => Yii::t('common', 'Are you sure you want to perform this operation?'),
											],
										],
									];
								}
								$items[] = [
									'options' => [
										'class' => 'authentication',
									],
									'label' => '<span class="fa fa-'. (Yii::$app->user->isGuest ? 'user-plus' : 'user') . '"></span>',
									'items' => $subItems,
								];
								$items = array_merge($items, $topBarMenu->getNestedItems(null, \common\models\MenuItem::STATUS_ACTIVE));
							?>
							<?= \common\widgets\sitemenu\SiteNav::widget([
								'options' => [
								],
								'activateParents' => true,
								'encodeLabels' => false,
								'items' => $items,
							]) ?>
						<?php endif; ?>
					</div>
					<!-- /.navigation start-->
				</div>
				<div class="col-lg-1 col-md-1 hidden hidden-sm hidden-xs">
					<div class="search">
						<!-- Large modal -->
						<a href="#" class="" data-toggle="modal" data-target=".search-modal"><i class="fa fa-search"></i></a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

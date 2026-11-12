<?php

/* @var $this yii\web\View */

use tws\helpers\Url;

$this->title = Yii::t('common', 'Dashboard');
$this->params['breadcrumbs'][] = $this->title;
$this->params['bodyAttributes'] = [
	'class' => ['page-container-bg-solid'],
];
?>

<div class="row">
	<?php if (Yii::$app->user->can('viewDossier')): ?>
		<div class="col-lg-4 col-md-6">
			<a class="dashboard-stat dashboard-stat-v2 blue-hoki" href="<?= Url::to(['/dossier-manager/dossier/index']) ?>">
				<div class="visual">
					<i class="icon-folder-alt"></i>
				</div>
				<div class="details">
					<div class="number">
						<span data-counter="counterup"><?= Yii::t('backend', 'Dossier') ?></span>
					</div>
				</div>
			</a>
		</div>
	<?php endif; ?>
	<?php if (Yii::$app->user->can('viewUser')): ?>
		<div class="col-lg-4 col-md-6">
			<a class="dashboard-stat dashboard-stat-v2 blue-hoki" href="<?= Url::to(['/user-manager']) ?>">
				<div class="visual">
					<i class="icon-people"></i>
				</div>
				<div class="details">
					<div class="number">
						<span data-counter="counterup"><?= Yii::t('common', 'Users') ?></span>
					</div>
				</div>
			</a>
		</div>
	<?php endif; ?>
	<?php if (Yii::$app->user->can('updateGeneralSetting')): ?>
		<div class="col-lg-4 col-md-6">
			<a class="dashboard-stat dashboard-stat-v2 blue-hoki" href="<?= Url::to(['/setting-manager']) ?>">
				<div class="visual">
					<i class="icon-settings"></i>
				</div>
				<div class="details">
					<div class="number">
						<span data-counter="counterup"><?= Yii::t('common', 'Settings') ?></span>
					</div>
				</div>
			</a>
		</div>
	<?php endif; ?>
</div>


<?php
/* @var $this yii\web\View */
/* @var $resetPasswordRequestModel common\models\ResetPasswordRequestForm */
/* @var $resetPasswordModel common\models\ResetPasswordForm */

use common\models\ResetPasswordForm;
use common\widgets\ActiveForm;
use yii\helpers\Html;

$this->params['breadcrumbs'][] = Html::encode($this->title);
?>

<div class="space-small">
	<div class="container">
		<?php if ($resetPasswordModel->scenario == ResetPasswordForm::SCENARIO_PASSWORD): ?>
			<div class="row">
				<div class="col-md-offset-3 col-md-6">
					<?php $form = ActiveForm::begin([
						'id' => mb_strtolower($resetPasswordModel->formName()),
						'options' => [
							'novalidate' => true,
							'class' => 'panel panel-default',
						],
						'validateOnType' => true,
						'validateOnBlur' => false,
					]); ?>
						<div class="panel-heading">
							<h2 class="panel-title"><?= Yii::t('common', 'Set your new password') ?></h2>
						</div>
						<div class="panel-body">
							<?= $form->field($resetPasswordModel, 'password')->passwordInput() ?>
							<?= $form->field($resetPasswordModel, 'verifyCode', [
								'options' => [
									'class' => 'form-verify-field',
								],
								'template' => '{input}',
							])->textInput()->label(false) ?>
						</div>
						<div class="panel-footer text-center">
							<?= Html::submitButton(Yii::t('common', 'Reset Password'), ['class' => 'btn btn-lg btn-block btn-default btn-slide-up']) ?>
						</div>
					<?php ActiveForm::end(); ?>
				</div>
			</div>
		<?php else: ?>
			<div class="row">
				<div class="col-md-6">
					<?php $form = ActiveForm::begin([
						'id' => mb_strtolower($resetPasswordRequestModel->formName()),
						'options' => [
							'novalidate' => true,
							'class' => 'panel panel-default',
						],
						'validateOnType' => true,
						'validateOnBlur' => false,
					]); ?>
						<div class="panel-heading">
							<h2 class="panel-title"><?= Yii::t('common', 'Request a password reset code') ?></h2>
						</div>
						<div class="panel-body">
							<?= $form->field($resetPasswordRequestModel, 'username')->textInput() ?>
							<?= $form->field($resetPasswordRequestModel, 'verifyCode', [
								'options' => [
									'class' => 'form-verify-field',
								],
								'template' => '{input}',
							])->textInput()->label(false) ?>
						</div>
						<div class="panel-footer text-center">
							<?= Html::submitButton(Yii::t('common', 'Request Code'), ['class' => 'btn btn-lg btn-block btn-default btn-slide-up']) ?>
						</div>
					<?php ActiveForm::end(); ?>
				</div>
				<div class="col-md-6">
					<?php $form = ActiveForm::begin([
						'id' => mb_strtolower($resetPasswordModel->formName()),
						'options' => [
							'novalidate' => true,
							'class' => 'panel panel-default',
						],
						'validateOnType' => true,
						'validateOnBlur' => false,
					]); ?>
						<div class="panel-heading">
							<h2 class="panel-title"><?= Yii::t('common', 'Already have a password reset code?') ?></h2>
						</div>
						<div class="panel-body">
							<?= $form->field($resetPasswordModel, 'token')->textInput() ?>
							<?= $form->field($resetPasswordModel, 'verifyCode', [
								'options' => [
									'class' => 'form-verify-field',
								],
								'template' => '{input}',
							])->textInput()->label(false) ?>
						</div>
						<div class="panel-footer text-center">
							<?= Html::submitButton(Yii::t('common', 'Validate Code'), ['class' => 'btn btn-lg btn-block btn-default btn-slide-up']) ?>
						</div>
					<?php ActiveForm::end(); ?>
				</div>
			</div>
		<?php endif; ?>

		<div class="text-center gap-t-sm">
			<p><?= Yii::t('common', 'Already have an account?') ?> <?= Html::a(Yii::t('common', 'Log In'), ['/site/login']) ?></p>
		</div>
	</div>
</div>

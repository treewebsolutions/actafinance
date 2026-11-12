<?php
/* @var $this yii\web\View */
/* @var $model common\models\LoginForm */

use common\widgets\ActiveForm;
use yii\helpers\Html;

$this->params['breadcrumbs'][] = Html::encode($this->title);
?>

<div class="space-small">
	<div class="container">
		<div class="row">
			<div class="col-md-offset-3 col-md-6">
				<?php $form = ActiveForm::begin([
					'id' => mb_strtolower($model->formName()),
					'options' => [
						'novalidate' => true,
						'class' => 'panel panel-default',
					],
					'validateOnType' => true,
					'validateOnBlur' => false,
				]); ?>
					<div class="panel-body">
						<?= $form->field($model, 'username')->input('email') ?>
						<?= $form->field($model, 'password')->passwordInput() ?>
						<div class="clearfix form-group">
							<?= $form->field($model, 'rememberMe', [
								'options' => [
									'class' => 'pull-left',
								],
							])->checkbox() ?>
							<?= Html::a(Yii::t('common', 'Forgot Password?'), ['/site/reset-password'], ['class' => 'pull-right']) ?>
						</div>
						<?= $form->field($model, 'verifyCode', [
							'options' => [
								'class' => 'form-verify-field',
							],
							'template' => '{input}',
						])->textInput()->label(false) ?>
					</div>
					<div class="panel-footer text-center">
						<?= Html::submitButton(Yii::t('common', 'Log In'), ['class' => 'btn btn-lg btn-block btn-default btn-slide-up', 'style' => 'height: inherit;']) ?>
					</div>
				<?php ActiveForm::end(); ?>
				<div class="text-center gap-t-sm">
					<p><?= Yii::t('common', 'Don\'t have an account yet?') ?> <?= Html::a(Yii::t('common', 'Sign Up'), ['/site/signup']) ?></p>
				</div>
			</div>
		</div>
	</div>
</div>

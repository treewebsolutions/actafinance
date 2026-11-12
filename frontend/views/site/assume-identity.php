<?php
/* @var $this yii\web\View */
/* @var $model common\models\LoginForm */

use common\widgets\ActiveForm;
use yii\helpers\Html;
?>

<div class="section">
	<div class="container-fluid">
		<?php if ($content = $this->context->currentPage->translation->content): ?>
			<header class="section-header speech-bubble speech-bubble-default gap-b-lg">
				<span class="speech-bubble-figure icon-logo-symbol"></span>
				<div class="speech-bubble-body"><?= $content ?></div>
			</header>
		<?php endif; ?>

		<div class="row">
			<div class="col-md-offset-3 col-md-6">
				<?php $form = ActiveForm::begin([
					'id' => mb_strtolower($model->formName()),
					'options' => [
						'novalidate' => true,
					],
					'validateOnType' => true,
					'validateOnBlur' => false,
				]); ?>
					<div class="clearfix form-group">
						<?= $form->field($model, 'rememberMe', [
							'options' => [
								'class' => 'pull-left',
							],
						])->checkbox() ?>
					</div>
					<?= $form->field($model, 'verifyCode', [
						'options' => [
							'class' => 'form-verify-field',
						],
						'template' => '{input}',
					])->textInput()->label(false) ?>
					<div class="text-center">
						<?= Html::submitButton(Yii::t('common', 'Log In'), ['class' => 'btn btn-lg btn-extra-radius btn-default btn-slide-center-v']) ?>
					</div>
				<?php ActiveForm::end(); ?>
			</div>
		</div>
	</div>
</div>

<?php
/* @var $this yii\web\View */
/* @var $model common\models\ActivateAccountForm */

use common\widgets\ActiveForm;
use yii\helpers\Html;

$this->params['breadcrumbs'][] = Html::encode($this->title);
?>

<div class="space-small">
	<div class="container">
		<?php if ($content = $this->context->currentPage->content): ?>
			<header class="section-header gap-b-xlg"><?= $content ?></header>
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
					<?= $form->field($model, 'token')->textInput() ?>
					<?= $form->field($model, 'verifyCode', [
						'options' => [
							'class' => 'form-verify-field',
						],
						'template' => '{input}',
					])->textInput()->label(false) ?>
					<div class="text-center">
						<button type="submit" class="btn btn-lg btn-default btn-slide-up"><?= Yii::t('common', 'Activate') ?></button>
					</div>
				<?php ActiveForm::end(); ?>
				<div class="text-center gap-t-sm">
					<p><?= Yii::t('common', 'Already have an account?') ?> <?= Html::a(Yii::t('common', 'Log In'), ['/site/login']) ?></p>
				</div>
			</div>
		</div>
	</div>
</div>

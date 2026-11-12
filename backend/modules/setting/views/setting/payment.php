<?php

/* @var $this yii\web\View */
/* @var $model backend\modules\setting\models\EmailSettingForm */
/* @var $form backend\widgets\ActiveForm */

use backend\widgets\ActiveForm;
use common\models\PaymentMetadata;
use kartik\touchspin\TouchSpin;
use yii\helpers\Html;

$this->title = Yii::t('backend', 'Payment Settings');
$this->params['breadcrumbs'] = [
	[
		'label' => Yii::t('common', 'Settings'),
		'url' => ['index'],
	],
	$this->title,
];
?>

<?php $form = ActiveForm::begin([
	'options' => [
		'novalidate' => true,
	],
	'validateOnType' => true,
]); ?>
	<?= $form->field($model, 'enableInvoiceEmailNotification')->checkbox() ?>
	<?= $form->field($model, 'attachInvoiceToEmail')->checkbox() ?>
	<?php
	$paymentMethodLabels = PaymentMetadata::getPaymentMethodLabels();
	$paymentProcessorLabels = PaymentMetadata::getPaymentProcessorLabels();
	?>
	<div class="panel blue-hoki">
		<div class="panel-title">
			<div class="panel-heading"><?= Yii::t('backend', 'Payment Methods') ?></div>
		</div>
		<div class="panel-body field-<?= Html::getInputId($model, 'paymentMethods') ?>">
			<?php $paymentMethod = PaymentMetadata::PAYMENT_METHOD_CASH; ?>
			<div class="panel panel-default">
				<div class="panel-heading">
					<?= $form->field($model, "paymentMethods[{$paymentMethod}]", [
						'options' => [
							'tag' => false,
						],
						'selectors' => [
							'container' => '.field-' . Html::getInputId($model, 'paymentMethods'),
							'input' => '.input-' . Html::getInputId($model, 'paymentMethods'),
							'error' => '.error-' . Html::getInputId($model, 'paymentMethods'),
						],
					])->checkbox([
						'id' => Html::getInputId($model, 'paymentMethods') . "-{$paymentMethod}",
						'class' => 'input-' . Html::getInputId($model, 'paymentMethods'),
						'value' => $paymentMethod,
						'label' => $paymentMethodLabels[$paymentMethod],
						'labelOptions' => [
							'class' => 'mt-checkbox mt-checkbox-outline m0',
						],
						'uncheck' => null,
					]) ?>
				</div>
			</div>
			<?php $paymentMethod = PaymentMetadata::PAYMENT_METHOD_CARD; ?>
			<div class="panel panel-default">
				<div class="panel-heading">
					<?= $form->field($model, "paymentMethods[{$paymentMethod}]", [
						'options' => [
							'tag' => false,
						],
						'selectors' => [
							'container' => '.field-' . Html::getInputId($model, 'paymentMethods'),
							'input' => '.input-' . Html::getInputId($model, 'paymentMethods'),
							'error' => '.error-' . Html::getInputId($model, 'paymentMethods'),
						],
					])->checkbox([
						'id' => Html::getInputId($model, 'paymentMethods') . "-{$paymentMethod}",
						'class' => 'input-' . Html::getInputId($model, 'paymentMethods'),
						'value' => $paymentMethod,
						'label' => $paymentMethodLabels[$paymentMethod],
						'labelOptions' => [
							'class' => 'mt-checkbox mt-checkbox-outline m0',
						],
						'uncheck' => null,
						'data' => [
							'toggle-visibility' => "#pm-{$paymentMethod}-container",
						],
					]) ?>
				</div>
				<div id="pm-<?= $paymentMethod ?>-container" class="panel-body field-<?= Html::getInputId($model, 'paymentProcessors') ?> <?= isset($model->paymentMethods[$paymentMethod]) ? '' : 'hidden' ?>">
					<?php $paymentProcessor = PaymentMetadata::PAYMENT_PROCESSOR_TWISPAY; ?>
					<fieldset>
						<legend>
							<?= $form->field($model, "paymentProcessors[{$paymentMethod}][{$paymentProcessor}]", [
								'options' => [
									'tag' => false,
								],
								'selectors' => [
									'container' => '.field-' . Html::getInputId($model, 'paymentProcessors'),
									'input' => '.input-' . Html::getInputId($model, 'paymentProcessors'),
									'error' => '.error-' . Html::getInputId($model, 'paymentProcessors'),
								],
							])->checkbox([
								'id' => Html::getInputId($model, 'paymentProcessors') . "-{$paymentProcessor}",
								'class' => 'input-' . Html::getInputId($model, 'paymentProcessors'),
								'value' => $paymentProcessor,
								'label' => $paymentProcessorLabels[$paymentProcessor],
								'uncheck' => null,
								'data' => [
									'toggle-visibility' => "#pp-{$paymentProcessor}-fields",
								],
							]) ?>
						</legend>
						<div id="pp-<?= $paymentProcessor ?>-fields" class="<?= in_array($paymentProcessor, (array) $model->paymentProcessors[$paymentMethod]) ? '' : 'hidden' ?>">
							<div class="text-info-icon fa-info-circle text-muted margin-bottom-10">
								<a href="https://twis.li/hosted-page-docs" target="_blank">
									<?= Yii::t('backend', 'Follow the official documentation for more information.') ?>
								</a>
							</div>
							<div class="row">
								<div class="col-lg-6">
									<?= $form->field($model, 'twispayBaseUrl')->input('url')->hint(Yii::t('backend', 'The API endpoint.') . ' ' . Yii::t('common', 'Example') . ': https://api.twispay.com/') ?>
								</div>
								<div class="col-lg-6">
									<?= $form->field($model, 'twispayPaymentUrl')->input('url')->hint(Yii::t('backend', 'The payment URL.') . ' ' . Yii::t('common', 'Example') . ': https://secure.twispay.com/') ?>
								</div>
							</div>
							<div class="row">
								<div class="col-lg-6">
									<?= $form->field($model, 'twispayApiKey')->textInput()->hint(Yii::t('backend', 'The API key.') . ' ' . Yii::t('common', 'Example') . ': abcdefghijklmnopqrstuvwxyz') ?>
								</div>
								<div class="col-lg-6">
									<?= $form->field($model, 'twispaySiteId')->textInput()->hint(Yii::t('backend', 'The API site ID.') . ' ' . Yii::t('common', 'Example') . ': 1234') ?>
								</div>
							</div>
						</div>
					</fieldset>
					<div class="error-<?= Html::getInputId($model, 'paymentProcessors') ?> help-block help-block-error"></div>
				</div>
			</div>
			<?php $paymentMethod = PaymentMetadata::PAYMENT_METHOD_BANK; ?>
			<div class="panel panel-default m0">
				<div class="panel-heading">
					<?= $form->field($model, "paymentMethods[{$paymentMethod}]", [
						'options' => [
							'tag' => false,
						],
						'selectors' => [
							'container' => '.field-' . Html::getInputId($model, 'paymentMethods'),
							'input' => '.input-' . Html::getInputId($model, 'paymentMethods'),
							'error' => '.error-' . Html::getInputId($model, 'paymentMethods'),
						],
					])->checkbox([
						'id' => Html::getInputId($model, 'paymentMethods') . "-{$paymentMethod}",
						'class' => 'input-' . Html::getInputId($model, 'paymentMethods'),
						'value' => $paymentMethod,
						'label' => $paymentMethodLabels[$paymentMethod],
						'labelOptions' => [
							'class' => 'mt-checkbox mt-checkbox-outline m0',
						],
						'uncheck' => null,
						'data' => [
							'toggle-visibility' => "#pm-{$paymentMethod}-container",
						],
					]) ?>
				</div>
				<div id="pm-<?= $paymentMethod ?>-container" class="panel-body <?= isset($model->paymentMethods[$paymentMethod]) ? '' : 'hidden' ?>">
					<div class="row">
						<div class="col-lg-6">
							<?= $form->field($model, 'bankName')->textInput() ?>
						</div>
						<div class="col-lg-6">
							<?= $form->field($model, 'bankAccount')->textInput() ?>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-6">
							<?= $form->field($model, 'bankBic')->textInput() ?>
						</div>
						<div class="col-lg-6">
							<?= $form->field($model, 'bankSwift')->textInput() ?>
						</div>
					</div>
				</div>
			</div>
			<div class="error-<?= Html::getInputId($model, 'paymentMethods') ?> help-block help-block-error"></div>
		</div>
	</div>

	<div class="form-actions floating">
		<?= Html::submitButton('<span class="fa fa-check"></span>', [
			'class' => 'btn btn-xlg btn-fab btn-success',
			'title' => Yii::t('common', 'Save'),
			'data' => [
				'toggle' => 'tooltip',
			],
		]) ?>
	</div>
<?php ActiveForm::end(); ?>

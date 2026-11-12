<?php
/* @var $this yii\web\View */
/* @var $form backend\widgets\ActiveForm */
/* @var $model common\models\Chapter */

use common\models\Chapter;
use backend\widgets\ActiveForm;
use kartik\file\FileInput;
use kartik\select2\Select2;
use kartik\touchspin\TouchSpin;
use tws\helpers\Url;
use yii\bootstrap\Tabs;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\JsExpression;

?>

<?php $form = ActiveForm::begin([
    'id' => mb_strtolower($model->formName()),
    'options' => [
        'novalidate' => true,
        'class' => Yii::$app->request->isAjax ? 'modal-dialog modal-lg' : '',
    ],
    'validateOnType' => true,
]); ?>
<div class="form-body <?= Yii::$app->request->isAjax ? 'modal-content' : '' ?>">
    <?php if (Yii::$app->request->isAjax) : ?>
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <div class="modal-title"><?= $this->title ?></div>
        </div>
    <?php endif; ?>

    <div class="form-fields <?= Yii::$app->request->isAjax ? 'modal-body' : '' ?>">
        <div class="row">
            <div class="col-sm-4">
                <?= $form->field($model, 'status')->widget(Select2::class, [
                    'data' => ArrayHelper::getColumn(Chapter::getStatusLabels(), 'label'),
                    'pluginLoading' => false,
                    'pluginOptions' => [
                        'allowClear' => false,
                        'placeholder' => Yii::t('common', 'Choose'),
                    ],
                ]) ?>
            </div>
            <div class="col-sm-4">
                <?= $form->field($model, 'sort_order')->widget(TouchSpin::class, [
                    'pluginOptions' => [
                        'min' => 1,
                        'max' => PHP_INT_MAX,
                        'step' => 1,
                        'decimals' => 0,
                        'boostat' => 5,
                        'maxboostedstep' => 10,
                        'verticalbuttons' => true,
                    ],
                ]) ?>
            </div>
			<div class="col-sm-4">
				<?= $form->field($model, 'rate')->widget(TouchSpin::class, [
					'pluginOptions' => [
						'min' => 1,
						'max' => 26,
						'step' => 1,
						'decimals' => 0,
						'boostat' => 5,
						'maxboostedstep' => 10,
						'verticalbuttons' => true,
					],
				]) ?>
			</div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <?= $form->field($model, 'parent_id')->widget(Select2::class, [
                    'options' => [
                        'multiple' => false,
                    ],
                    'data' => ArrayHelper::map(Chapter::findAllAvailableChapters($model->id), 'id', 'treeName'),
                    'maintainOrder' => true,
                    'showToggleAll' => false,
                    'pluginLoading' => false,
                    'pluginOptions' => [
                        'allowClear' => true,
                        'placeholder' => Yii::t('common', 'Choose'),
						'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                    ],
                ]) ?>
            </div>
        </div>
        <fieldset class="fieldset margin-bottom-10">
            <legend><?= Yii::t('label', 'Figure') ?></legend>
            <div class="row">
                <div class="col-sm-6">
                    <?= $form->field($model, 'imageFile')->widget(FileInput::class, [
                        'options' => [
                            'accept' => 'image/*',
                            'data' => [
                                'operation-confirm' => Yii::t('common', 'Are you sure you want to perform this operation?'),
                            ],
                        ],
                        'resizeImages' => false,
                        'sortThumbs' => false,
                        'purifyHtml' => false,
                        'pluginOptions' => [
                            'allowedFileExtensions' => ['jpeg', 'jpg', 'png', 'gif'],
                            'maxFileSize' => Yii::$app->settings->get('maxFileSize'),
                            'dropZoneEnabled' => false,
                            'showClose' => false,
                            'showUpload' => false,
                            'showCaption' => true,
                            'showRemove' => true,
                            'showPreview' => true,
                            'fileActionSettings' => [
                                'showDownload' => true,
                                'showRemove' => true,
                                'showUpload' => false,
                                'showZoom' => true,
                                'showDrag' => false,
                            ],
                            'initialPreview' => $model->imageUrl ?: false,
                            'initialPreviewConfig' => [
                                [
                                    'downloadUrl' => $model->imageUrl,
                                ],
                            ],
                            'initialPreviewAsData' => true,
                            'initialPreviewShowDelete' => true,
                            'overwriteInitial' => true,
                            'deleteUrl' => Url::to(['delete-file', 'id' => $model->id]),
                            'deleteExtraData' => [
                                'attribute' => 'image',
                            ],
                        ],
                    ]) ?>
                </div>
                <div class="col-sm-6">
                    <?= $form->field($model, 'icon')->widget(Select2::class, [
                        'data' => \common\helpers\FontIcon::getDropdownIcons(),
                        'pluginLoading' => false,
                        'pluginOptions' => [
                            'allowClear' => true,
                            'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                            'placeholder' => Yii::t('common', 'Choose'),
                        ],
                    ]) ?>
                </div>
            </div>
            <div class="text-info-icon fa-info-circle text-muted margin-top-10"><?= Yii::t('backend', 'If there is more than one figure selected, the image it will be displayed first and the icon last.') ?></div>
        </fieldset>
        <?php
        $i18nFields = [];
        foreach (\common\models\Language::findAllLanguages() as $language) {
            $i18nFields[] = [
                'label' => mb_strtoupper($language->language),
                'content' => $this->render('_i18n-fields', [
                    'model' => $model,
                    'form' => $form,
                    'language' => $language,
                ]),
                'active' => $language->language_id === Yii::$app->language,
            ];
        }
        ?>
        <?= Tabs::widget([
            'items' => $i18nFields,
        ]) ?>
    </div>

    <?php if (Yii::$app->request->isAjax) : ?>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal"><?= Yii::t('common', 'Cancel') ?></button>
            <?= Html::submitButton(Yii::t('common', 'Save'), ['class' => 'btn btn-success']) ?>
        </div>
    <?php else : ?>
        <div class="form-actions floating">
            <?= Html::submitButton('<span class="fa fa-check"></span>', [
                'class' => 'btn btn-xlg btn-fab btn-success',
                'title' => Yii::t('common', 'Save'),
                'data' => [
                    'toggle' => 'tooltip',
                ],
            ]) ?>
        </div>
    <?php endif; ?>
</div>
<?php ActiveForm::end(); ?>
